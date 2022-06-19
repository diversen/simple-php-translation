// Example of how to implement Lang.translate in javascript ...
// import Cookies from 'https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.mjs'

function replaceSubstitutions(str, substitutions) {

    for (let substitution in substitutions) {
        let search = '{' + substitution + '}';
        str = str.replace(search, substitutions[substitution])
    }

    return str;
}

class Translate {

    constructor() {
        this.Translation = [];
    }

    async load(language) {
        // Maybe control js language using cookies
        // let language = Cookies.get('language');
        // if (!language) language = 'en';

        const modulePath = '/js/lang/' + language + '/language.js';

        let { Translation} = await import(modulePath)
        this.Translation = Translation;
    }

    translate(str, substitutions) {

        let toTranslate = this.Translation[str];
        if (!toTranslate) toTranslate = str;

        if (substitutions) {
            toTranslate = replaceSubstitutions(toTranslate, substitutions);
        }
        return toTranslate;
    }
}

let Lang = new Translate();

export {Lang};