export interface MasteriyoLocalized {
	rootApiUrl: string;
	nonce: string;
	logo: string | string[];
	siteTitle: string;
	userAvatar: string;
	qaEnable: string;
	urls: {
		logout: string;
		account: string;
		courses: string;
		home: string;
	};
	isUserLoggedIn: boolean;
	settings: {
		styling: {
			primary_color: string;
			theme: string;
		};
	};
}
