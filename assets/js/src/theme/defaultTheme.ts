export default {
	button: {
		base:
			'mto-flex mto-justify-center mto-items-center mto-border mto-border-solid mto-font-medium mto-rounded-sm mto-transition-all mto-duration-300 mto-ease-in-out',
		block: 'w-full',
		size: {
			small: 'mto-px-2 mto-py-2 mto-text-xs',
			medium: 'mto-px-4 mto-py-3 mto-text-xs',
			large: 'mto-px-5 mto-py-4 mto-text-s',
		},
		primary: {
			base: 'mto-border-primary mto-bg-primary mto-text-white',
			active: 'hover:mto-bg-primary-600 hover:mto-border-primary-700',
			disabled: 'bg-gray-50 border-gray-200 text-gray-200',
		},
		accent: {
			base: 'mto-border-accent mto-bg-accent mto-text-white',
			active: 'hover:mto-bg-accent-600 hover:mto-border-accent-700',
			disbabled: 'bg-gray-50 border-gray-200 text-gray-200',
		},
		outline: {
			base: 'mto-border-gray-300 text-gray-700',
			active: 'hover:mto-border-primary hover:mto-text-primary',
			disbabled: 'bg-gray-50 border-gray-200 text-gray-200',
		},
	},
};
