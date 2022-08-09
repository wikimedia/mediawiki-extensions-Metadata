mw.metadata = {

    get: function ( key ) {
        var id = mw.config.get( 'wgArticleId' );
        return $.get( '/w/rest.php/metadata/v0/' + id + '/' + key );
    },

    set: function ( key, value ) {
        var id = mw.config.get( 'wgArticleId' );
        return $.get( '/w/rest.php/metadata/v0/' + id + '/' + key + '/' + value );
   }
};