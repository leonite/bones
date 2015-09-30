module.exports = {
	
	/*cssmin: {
	
	target: {
		
		files: [{
			
			expand: true,
			cwd: 'library/css',
			src: ['*.css', '!*.min.css'],
			dest: 'library/css',
			ext: '.min.css'
		
		}]
	
	}

	} */
	
	combine: {
		
		files: {
			
			'library/css/compiled/build.css': ['library/css/style.css','library/css/login.css', 'library/css/editor-style.css', 'library/css/ie.css']
		
		}
	
	} 

}


