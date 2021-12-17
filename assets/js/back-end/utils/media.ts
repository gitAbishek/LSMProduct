import urls from '../constants/urls';
import http from './http';

class MediaAPI {
	uri: string;

	constructor() {
		this.uri = `${urls.wpMedia}/`;
	}

	async get(id: number) {
		return http({
			path: this.uri + id,
			method: 'GET',
		}).then((res: any) => res);
	}

	async store(data: any) {
		return http({
			path: this.uri,
			method: 'POST',
			data: data,
			headers: {
				'Content-Type': 'multipart/form-data',
			},
		}).then((res: any) => res);
	}

	async delete(id: number) {
		return http({
			path: this.uri + id + '?force=true',
			method: 'POST',
			headers: {
				'x-http-method-override': 'DELETE',
			},
		}).then((res: any) => res);
	}

	async list(query?: any) {
		return http({
			path: query ? `${this.uri}?${query}` : this.uri,
			method: 'GET',
		}).then((res: any) => res);
	}
}

export default MediaAPI;
