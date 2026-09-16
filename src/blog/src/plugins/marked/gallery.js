export const galleryExtension = {
    name: 'gallery',
    level: 'block',
    start(src) { return src.match(/^:::/)?.index; },
    tokenizer(src) {
        const match = /^::: gallery\s*\n([\s\S]*?)\n:::/.exec(src);
        if (match) {
            return {
                type: 'gallery',
                raw: match[0],
                tokens: this.lexer.inlineTokens(match[1].trim())
            };
        }
    },
    renderer(token) {
        return `<div class="gallery">${this.parser.parseInline(token.tokens)}</div>`;
    }
}
