import type { ImageResponse, ImageCreateRequest } from "../types/image";

const API_BASE = import.meta.env.VITE_API_BASE;

const getHeaders = () => {
    const token = localStorage.getItem('snapToken') || '';
    return {
        'X-AUTH-TOKEN': token,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };
};

export const apiClient = {
    async getImages(): Promise<ImageResponse[]> {
        const result = await fetch(`${API_BASE}/image`, { headers: getHeaders() });
        if (!result.ok) throw new Error(`Fetch failed: ${result.statusText}`);
        return result.json();
    },

    async registerImage(data: ImageCreateRequest): Promise<ImageResponse> {
        const result = await fetch(`${API_BASE}/image`, {
            method: 'POST', headers: getHeaders(), body: JSON.stringify(data),
        })
        if (!result.ok) throw new Error(`Unable to create image ${data.slug} - ${result.statusText}`)

        return result.json()
    },

    async deleteImage(slug: string): Promise<void> {
        const result = await fetch(`${API_BASE}/image/${slug}`, { method: 'DELETE', headers: getHeaders() })
        if (!result.ok) throw new Error(`Unable to delete ${slug} - ${result.statusText}`)
    }
}
