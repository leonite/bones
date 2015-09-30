module.exports = {
	
	dynamic: {
    
		files: [{
		
			expand: true,
			cwd: 'library/images/',
			src: ['*.{png,jpg,gif}'],
			dest: 'library/images/compressed'
		
		}]
	
	}
	
}