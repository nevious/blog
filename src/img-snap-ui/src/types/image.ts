
export interface ImageResponse {
    id: number
    slug: string
    upstreamUrl: string
}

export interface ImageCreateRequest {
    slug: string
    upstreamUrl: string
}
