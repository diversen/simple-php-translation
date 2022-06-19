
// This will add js strings to be translated to the translation files.  
let Lang = new TranslateLang();

// The extractor looks for `Lang.translate( ... )
Lang.translate('Activity this week: <span class="notranslate">{week_user_total}</span>', {'week_user_total': 100});


