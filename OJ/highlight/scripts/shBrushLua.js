/**
 * SyntaxHighlighter
 * http://alexgorbatchev.com/SyntaxHighlighter
 *
 * SyntaxHighlighter is donationware. If you are using it, please donate.
 * http://alexgorbatchev.com/SyntaxHighlighter/donate.html
 *
 * @version
 * 3.0.83 (July 02 2010)
 *
 * @copyright
 * Copyright (C) 2004-2010 Alex Gorbatchev.
 *
 * @license
 * Dual licensed under the MIT and GPL licenses.
 */
;(function()
{
    // CommonJS
    typeof(require) != 'undefined' ? SyntaxHighlighter = require('shCore').SyntaxHighlighter : null;
  
    function Brush()
    {
        // maker is IkPil, Choi
        // http://www.ikpil.com
              
        var keywords = 'and break do else elseif end false for function if in ' +
                       'local nil not or repeat return then true until while';
   
        var functions = '_G _VERSION assert collectgarbage dofile error getfenv ' +
                        'getmetatable ipairs load module next pairs pcall print ' +
                        ' rawequal rawget rawset require select setfenv setmetatable ' +
                        'tonumber tostring type unpack xpcall ' +
                        'coroutine.create coroutine.resume coroutine.running ' +
                        'coroutine.status coroutine.wrap coroutine.yield ' +
                        'debug.debug debug.getfenv debug.gethook debug.getinfo ' +
                        'debug.getlocal debug.getmetatable debug.getregistry ' +
                        'debug.getupvalue debug.setfenv debug.sethook debug.setlocal ' +
                        'debug.setmetatable debug.setupvalue debug.traceback ' +
                        'file:close file:flush file:lines file:read file:seek ' +
                        'file:setvbuf file:write' +
                        'io.close io.flush io.input io.lines io.open io.output ' +
                        'io.popen io.read io.stderr io.stdin io.stdout io.tmpfile ' +
                        'io.type io.write ' +
                        'math.abs math.acos math.asin math.atan math.atan2 math.ceil ' +
                        'math.cos math.cosh math.deg math.exp math.floor math.fmod ' +
                        'math.frexp math.huge math.ldexp math.log math.log10 math.max ' +
                        'math.min math.modf math.pi math.pow math.rad math.random ' +
                        'math.randomseed math.sin math.sinh math.sqrt math.tan math.tanh ' +
                        'os.clock os.date os.difftime os.execute os.exit os.getenv os.remove ' +
                        'os.rename os.setlocale os.time os.tmpname ' +
                        'package.cpath package.loaded package.loaders package.loadlib ' +
                        'package.path package.preload package.seeall ' +
                        'string.byte string.char string.dump string.find string.format ' +
                        'string.gmatch string.gsub string.len string.lower string.match ' +
                        'string.rep string.reverse string.sub string.upper table.concat ' +
                        'table.insert table.maxn table.remove table.sort';
          
        this.regexList = [
            { regex: /--.*/gm,                                                  css: 'comments' },  // one line comments
            { regex: /--\[\[[\S\s]*\]\]/gm,                                     css: 'comments' },  // multi line comments
            { regex: SyntaxHighlighter.regexLib.doubleQuotedString,             css: 'string' },    // strings
            { regex: SyntaxHighlighter.regexLib.singleQuotedString,             css: 'string' },    // strings
            { regex: SyntaxHighlighter.regexLib.multiLineDoubleQuotedString,    css: 'string' },    // strings
            { regex: SyntaxHighlighter.regexLib.multiLineSingleQuotedString,    css: 'string' },    // strings
            { regex: new RegExp(this.getKeywords(keywords), 'gm'),              css: 'keyword' },   // keywords
            { regex: new RegExp(this.getKeywords(functions), 'gm'),             css: 'functions' }  // functions
            ];
    }
  
    Brush.prototype = new SyntaxHighlighter.Highlighter();
    Brush.aliases   = ['lua'];
  
    SyntaxHighlighter.brushes.Lua = Brush;
  
    // CommonJS
    typeof(exports) != 'undefined' ? exports.Brush = Brush : null;
})();