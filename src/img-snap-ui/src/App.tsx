import { useState, useEffect, useCallback } from 'react';
import { apiClient } from './api/client';
import { Camera, RefreshCw, KeyRound } from 'lucide-react';
import RegisterForm from './features/management/RegisterForm';
import ImageCard from './features/gallery/ImageCard';
import type { ImageResponse } from './types/image';

function App() {
    const [images, setImages] = useState<ImageResponse[]>([]);
    const [loading, setLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);
    const [authToken, setAuthToken] = useState<string | null>(() => {
        return localStorage.getItem('snapToken')
    })
    const [isAuthOpen, setIsAuthOpen] = useState<boolean>(false)
    const [tokenInput, setTokenInput] = useState<string>('')

    const loadImages = useCallback(async () => {
        setLoading(true)
        setError(null)

        try {
            const data = await apiClient.getImages()
            setImages(data)
        } catch (err) {
            setError('Failed to load image from backend.')
            setImages([])
            console.log(err)
        } finally {
            setLoading(false)
        }
    }, []);

    /* useEffect is similar to onMounted in vuew */
    useEffect(() => {
        loadImages()
    }, [loadImages])

    const handleDelete = async (slug: string) => {
        if (!confirm(`Confirm deletion ${slug}`)) return;
        try {
            await apiClient.deleteImage(slug)
            setImages((prev) => prev.filter(img => img.slug !== slug))
        } catch (err) {
            setError(`Delete ${slug} failed.`)
            console.log(err)
        }
    };

    const handleRegisterSuccess = (newImage: ImageResponse) => {
        // prev stands for previous state, apparently a react "custom"
        setImages((prev) => [newImage, ...prev]);
    };

    const handleAuthorize = () => {
        if (!tokenInput) return;
        localStorage.setItem('snapToken', tokenInput)
        setAuthToken(tokenInput)
        setIsAuthOpen(false)
        loadImages()
    }

    const handleLogout = () => {
        localStorage.removeItem('snapToken')
        setTokenInput('')
        setAuthToken(null)
        setIsAuthOpen(false)
        loadImages()
    }

    return (
        <div className="min-h-screen bg-slate-50 p-4 md:p-8 text-slate-900 font-sans">
            <header className="max-w-6xl mx-auto mb-8 flex items-center justify-between">
                <h1 className="flex items-center gap-3 text-2xl md:text-3xl font-bold tracking-tight">
                    <div className="bg-indigo-600 p-2 rounded-lg text-white">
                        <Camera size={24} />
                    </div>
                    Snap Dashboard
                </h1>
                <div className="flex justify-items-end gap-2">
                    <button
                        onClick={loadImages}
                        className="flex items-center gap-2 rounded-lg bg-white px-4 py-2 shadow-sm hover:bg-slate-50 transition-colors border border-slate-200 font-medium"
                    >
                        <RefreshCw size={18} className={loading ? 'animate-spin' : ''} />
                        Refresh
                    </button>

                    <div className="relative">
                        <button
                            onClick={() => setIsAuthOpen(!isAuthOpen)}
                            className="flex items-center gap-2 rounded-lg bg-white px-4 py-2 shadow-sm hover:bg-slate-50 transition-colors border border-slate-200 font-medium"
                        >
                            {/* We use the token state to change the icon color as feedback */}
                            <KeyRound size={18} className={authToken ? "text-indigo-600" : "text-slate-400"} />
                            {authToken ? "Authorized" : "Authenticate"}
                        </button>

                        {/* The Popover Box */}
                        {isAuthOpen && (
                            <div className="absolute right-0 mt-2 w-72 bg-white border border-slate-200 rounded-xl shadow-xl p-4 z-50 animate-in fade-in slide-in-from-top-1">
                                <h3 className="text-sm font-semibold mb-2">Authorize Session</h3>
                                <input
                                    type="password"
                                    value={tokenInput}
                                    onChange={(e) => setTokenInput(e.target.value)}
                                    placeholder="Your API Token"
                                    className="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500"
                                />
                                <div className="flex gap-2 mt-4">
                                    <button onClick={handleAuthorize} className="flex-1 bg-indigo-600 text-white py-2 rounded-lg text-sm hover:bg-indigo-700 transition-colors">
                                        Authorize
                                    </button>
                                    <button onClick={handleLogout} className="flex-1 bg-slate-100 text-slate-600 py-2 rounded-lg text-sm hover:bg-slate-200 transition-colors">
                                        Clear
                                    </button>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </header>

            <main className="max-w-6xl mx-auto">
                {/* Registration Form */}
                { authToken && <RegisterForm onSuccess={handleRegisterSuccess} /> }

                {/* Loading & Error States */}
                {loading && (
                    <div className="flex items-center justify-center py-20">
                        <RefreshCw className="animate-spin text-slate-300" size={48} />
                    </div>
                )}

                {error && (
                    <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex justify-between items-center">
                        <p>{error}</p>
                        <button onClick={() => setError(null)} className="text-red-400 hover:text-red-600">&times;</button>
                    </div>
                )}

                {/* The Gallery */}
                {!loading && (
                    <>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            {images.map((image) => (
                            <ImageCard
                                key={image.id}
                                image={image}
                                onDelete={handleDelete}
                            />
                            ))}
                        </div>

                        {images.length === 0 && !error && (
                            <div className="text-center py-24 bg-white border-2 border-dashed border-slate-200 rounded-3xl">
                                <Camera size={48} className="mx-auto text-slate-200 mb-4" />
                                <h3 className="text-xl font-medium text-slate-400">No images registered yet.</h3>
                                <p className="text-slate-300 mt-1">Add your first proxy slug to get started.</p>
                            </div>
                        )}
                    </>
                )}
            </main>

            <footer className="max-w-6xl mx-auto mt-20 pb-10 text-center text-slate-400 text-sm">
                <p>&copy; {new Date().getFullYear()} Snap Image Proxy &middot; Learning Project</p>
            </footer>
        </div>
    );
}

export default App;
