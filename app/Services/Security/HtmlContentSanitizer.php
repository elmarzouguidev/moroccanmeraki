<?php

namespace App\Services\Security;

use DOMDocument;
use DOMElement;
use DOMNode;

class HtmlContentSanitizer
{
    private const ALLOWED_TAGS = [
        'a', 'b', 'blockquote', 'br', 'code', 'em', 'h2', 'h3', 'i', 'li',
        'ol', 'p', 'strong', 'u', 'ul',
    ];

    private const ALLOWED_ATTRIBUTES = [
        'a' => ['href', 'title'],
    ];

    public function sanitize(string $html): string
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="UTF-8"><div id="sanitizer-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('sanitizer-root');

        if (! $root) {
            return '';
        }

        foreach (iterator_to_array($root->childNodes) as $child) {
            $this->sanitizeNode($child);
        }

        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $document->saveHTML($child);
        }

        return trim((string) preg_replace_callback(
            '/%7B([a-z0-9_]+)%7D/i',
            static fn (array $matches): string => '{'.$matches[1].'}',
            $result
        ));
    }

    public function sanitizeText(string $value): string
    {
        return trim(strip_tags($value));
    }

    private function sanitizeNode(DOMNode $node): void
    {
        if (! $node instanceof DOMElement) {
            return;
        }

        $tag = strtolower($node->tagName);

        if (! in_array($tag, self::ALLOWED_TAGS, true)) {
            if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed', 'form'], true)) {
                $node->parentNode?->removeChild($node);

                return;
            }

            $this->unwrap($node);

            return;
        }

        foreach (iterator_to_array($node->attributes) as $attribute) {
            $name = strtolower($attribute->name);
            $allowed = self::ALLOWED_ATTRIBUTES[$tag] ?? [];

            if (! in_array($name, $allowed, true) || ($name === 'href' && ! $this->isSafeUrl($attribute->value))) {
                $node->removeAttribute($attribute->name);
            } elseif ($name === 'href' && preg_match('/^%7B([a-z0-9_]+)%7D$/i', $attribute->value, $matches)) {
                $node->setAttribute($attribute->name, '{'.$matches[1].'}');
            }
        }

        foreach (iterator_to_array($node->childNodes) as $child) {
            $this->sanitizeNode($child);
        }
    }

    private function unwrap(DOMElement $node): void
    {
        $parent = $node->parentNode;

        if (! $parent) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }

    private function isSafeUrl(string $url): bool
    {
        $url = trim($url);

        if (preg_match('/^\{[a-z0-9_]+\}$/i', $url)) {
            return true;
        }

        return (bool) preg_match('/^(https?:\/\/|mailto:)/i', $url);
    }
}
