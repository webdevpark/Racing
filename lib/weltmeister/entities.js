ig.module(
	'weltmeister.entityLoader'
)
.requires(
	'weltmeister.config'
)
.defines(function(){

// Load the list of entity files via AJAX PHP glob
var path = wm.config.api.glob 
	+ '?glob=' + encodeURIComponent( wm.config.project.entityFiles )
	+ '&nocache=' + Math.random();
	
var req = $.ajax({
	url: path, 
	method: 'get',
	dataType: 'json',
	
	// MUST load synchronous, as the engine would otherwise determine that it
	// can't resolve dependencies to weltmeister.entities when there are
	// no more files to load and weltmeister.entities is still not defined
	// because the ajax request hasn't finished yet.
	// FIXME FFS!
	async: false, 
	success: function(files) {
		
		// File names to Module names
		var moduleNames = [];
		for( var i = 0; i < files.length; i++ ) {
			moduleNames.push( files[i].replace(/^lib\/|\.js$/g,'').replace(/\//g, '.') );
		}
		
		// Define a Module that requires all entity Modules
		ig.module('weltmeister.entities')
			.requires.apply(ig, moduleNames)
			.defines(function(){ wm.entityFiles = files; });
	},
	error: function( xhr, status, error ){
		throw( 
			"Failed to load entity list via glob.php: " + error + "\n" +
			xhr.responseText
		);
	}
});

});