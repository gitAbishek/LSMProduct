export const getDeviceType = (): 'desktop' | 'tablet' | 'mobile' =>
	wp.data
		.select('core/edit-post')
		.__experimentalGetPreviewDeviceType()
		.toLowerCase();
