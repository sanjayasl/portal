<?php namespace Lio\Articles;

use McCool\LaravelAutoPresenter\BasePresenter;
use \Michelf\MarkdownExtra;
use App, Str;

class ArticlePresenter extends BasePresenter
{
    public function content()
    {
        return MarkdownExtra::defaultTransform($this->resource->content);
    }

    public function comment_count_label()
    {
        if ($this->resource->comment_count == 0) {
            return '0 Comments';
        } elseif($this->resource->comment_count == 1) {
            return '1 Comment';
        }

        return $this->resource->comment_count . ' Comments';
    }

    public function excerpt()
    {
        // kinda a mess but s'ok for now
        $html = App::make('Lio\Markdown\HtmlMarkdownConvertor')->convertMarkdownToHtml($this->resource->content);
        $text = strip_tags($html);
        if (false !== strpos($text, "\n\n")) {
            list($excerpt, $dump) = explode("\n\n", $text);
        } else {
            $excerpt = $text;
        }
        return Str::words($excerpt, 200);
    }

    public function published_at()
    {
        return $this->resource->published_at->toFormattedDateString();
    }

    public function published_ago()
    {
        return $this->resource->published_at->diffForHumans();
    }

    public function editUrl()
    {
        return action('ArticlesController@getEdit', [$this->resource->id]);
    }

    public function showUrl()
    {
        if ( ! $this->resource->slug) return '';

        return action('ArticlesController@getShow', [$this->resource->slug->slug]);
    }
}