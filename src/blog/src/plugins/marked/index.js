import { Marked } from 'marked'
import { galleryExtension } from './gallery'

const marked = new Marked()

marked.use({
    extensions: [galleryExtension]
})

export default marked

