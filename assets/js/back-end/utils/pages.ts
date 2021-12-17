import urls from '../constants/urls';
import http from './http';

class PagesAPI {
	uri: string;

	constructor() {
		this.uri = `${urls.wpPages}`;
	}

	async list() {
		return http({
			path: this.uri,
			method: 'get',
		}).then((res: any) => res);
	}
}

export default PagesAPI;
