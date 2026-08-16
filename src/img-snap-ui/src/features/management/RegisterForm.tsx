import React, { useState } from 'react';
import { apiClient } from '../../api/client';
import type { ImageResponse } from '../../types/image';
import { PlusCircle, Loader2 } from 'lucide-react';

interface RegisterFormProps {
  onSuccess: (newImage: ImageResponse) => void;
}

const RegisterForm: React.FC<RegisterFormProps> = ({ onSuccess }) => {
  const [slug, setSlug] = useState('');
  const [url, setUrl] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!slug || !url) return;

    setLoading(true);
    setError(null);

    try {
      const newImage = await apiClient.registerImage({ slug, upstreamUrl: url });
      setSlug('');
      setUrl('');
      onSuccess(newImage);
    } catch (err) {
      setError('Failed to register image. Ensure the slug is unique and the URL is valid.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-white p-6 rounded-xl border border-slate-200 shadow-sm mb-8">
      <h2 className="text-lg font-semibold mb-4 flex items-center gap-2">
        <PlusCircle size={20} className="text-indigo-600" />
        Register New Image Proxy
      </h2>
      
      <form onSubmit={handleSubmit} className="space-y-4">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label htmlFor="slug" className="block text-sm font-medium text-slate-700 mb-1">
              Slug (Unique Identifier)
            </label>
            <input
              id="slug"
              type="text"
              value={slug}
              onChange={(e) => setSlug(e.target.value)}
              placeholder="e.g. hero-banner"
              className="w-full px-4 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
              required
            />
          </div>
          <div>
            <label htmlFor="url" className="block text-sm font-medium text-slate-700 mb-1">
              Upstream URL
            </label>
            <input
              id="url"
              type="url"
              value={url}
              onChange={(e) => setUrl(e.target.value)}
              placeholder="https://nextcloud.example.com/s/..."
              className="w-full px-4 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
              required
            />
          </div>
        </div>

        {error && <p className="text-red-500 text-sm">{error}</p>}

        <button
          type="submit"
          disabled={loading || !slug || !url}
          className="w-full md:w-auto px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:bg-slate-300 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2"
        >
          {loading ? (
            <>
              <Loader2 size={18} className="animate-spin" />
              Registering...
            </>
          ) : (
            'Register Image'
          )}
        </button>
      </form>
    </div>
  );
};

export default RegisterForm;
