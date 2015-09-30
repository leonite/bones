module.exports = {
	
	build: {
		
		src: [
			
			'library/js/*.js'
		],
		
		dest: 'library/js/compiled/build.js'
	},
  
	thirdparty: {
   	
		src: [
		
			'library/js/libs/*.js'
		
		],
		
		dest: 'library/js/compiled/thirdparty.js'
  
	}
  
}