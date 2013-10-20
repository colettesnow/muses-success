// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// BBCode tags example
// http://en.wikipedia.org/wiki/Bbcode
// ----------------------------------------------------------------------------
// Feel free to add more tags
// ----------------------------------------------------------------------------
mySettings = {
        previewParserPath:        'http://muses-success.sorrowfulunfounded.com/forums/post/parse_bbcode/', // path to your BBCode parser
        markupSet: [
                {name:'Bold', key:'B', openWith:'[b]', closeWith:'[/b]'},
                {name:'Italic', key:'I', openWith:'[i]', closeWith:'[/i]'},
                {name:'Underline', key:'U', openWith:'[u]', closeWith:'[/u]'},
                {separator:'---------------' },
                {name:'Picture', key:'P', replaceWith:'[img][![Url]!][/img]'},
                {name:'Link', key:'L', openWith:'[url=[![Url]!]]', closeWith:'[/url]', placeHolder:'Your text to link here...'},
                {separator:'---------------' },
                {name:'Quotes', openWith:'[quote]', closeWith:'[/quote]'},
                {separator:'---------------' },
                {name:'Clean', className:"clean", replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },
                {name:'Preview', className:"preview", call:'preview' }
        ]
}
