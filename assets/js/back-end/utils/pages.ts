import urls from '../constants/urls';
import http from './http';
import { formatParams } from './utils';

class PagesAPI {
	uri: string;

	constructor() {
		this.uri = `${urls.wpPages}`;
	}

	async list(
		query: any = {
			per_page: -1,
		}
	) {
		return http({
			path: `${this.uri}?${formatParams(query)}`,
			method: 'get',
		}).then((res: any) => res);
	}
}

export default PagesAPI;
