import { useState, } from 'react';
import type { ImageResponse } from '../../types/image';
import { Trash2, ExternalLink, Check, ClipboardPlus, View } from 'lucide-react';

interface ImageCardProps {
    image: ImageResponse;
    onDelete: (slug: string) => void;
}

const ImageCard: React.FC<ImageCardProps> = ({image, onDelete}) => {
    const API_BASE = import.meta.env.VITE_API_BASE;
    const [isCopied, setIsCopied] = useState(false);

    const handleCopy = () => {
        const value = `${API_BASE}/store/${image.slug}`
        navigator.clipboard.writeText(value)
        setIsCopied(true)
        setTimeout(() => setIsCopied(false), 2000)
    }

    return (
        <>
        <div key={image.id} className="group relative bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
            <div className="aspect-video bg-slate-100 relative overflow-hidden group-hover:bg-slate-200 transition-colors">
                <img
                    src={`${API_BASE}/store/${image.slug}`}
                    alt={image.slug}
                    className="w-full h-full object-cover"
                    onError={(e) => {
                        (e.target as HTMLImageElement).src = 'https://placehold.co/600x400/e2e8f0/64748b?text=Preview+Not+Available';
                    }}
                />
                <div className="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors pointer-events-none" />
            </div>

            <div className="p-4 flex-grow flex flex-col">
                <div className="flex justify-between items-start mb-2">
                    <h2 className="font-bold text-lg text-slate-800">{image.slug}</h2>
                    <div className="flex gap-2">
                        <button
                            onClick={() => onDelete(image.slug)}
                            className="p-1.5 text-slate-400 hover:text-red-500 transition-colors"
                            title="Delete registration"
                        >
                            <Trash2 size={16} />
                        </button>

                        <a 
                            href={image.upstreamUrl} 
                            target="_blank" 
                            rel="noreferrer"
                            className="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors"
                            title="Source URL"
                        >
                            <ExternalLink size={16} />
                        </a>

                        <button
                            className="p-1.5 text-slate-400 hover:text-red-500 transition-colors hidden"
                            title="Preview"
                        >
                            <View size={16}/>
                        </button>

                        <button
                            onClick={handleCopy}
                            className="p-1.5 text-slate-400 hover:text-red-500 transition-colors"
                            title="Copyt to clipboard"
                        >
                            {isCopied ? <Check size={16} className="text-green-500"/> : <ClipboardPlus size={16} />}
                        </button>
                    </div>
                </div>
                <code className="text-[10px] bg-slate-50 p-2 rounded block break-all text-slate-500 mt-auto border border-slate-100">
                    {image.upstreamUrl}
                </code>
            </div>
        </div>
        </>
    )
}

export default ImageCard;
