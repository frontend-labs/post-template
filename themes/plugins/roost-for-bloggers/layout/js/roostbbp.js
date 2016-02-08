window._roostCallback = function( data ) {
    if ( data.registered ) {
        if ( data.enabled ) {
            roostToken = data.deviceToken;
            roostEnabled = data.enabled;
        }
    }
}
