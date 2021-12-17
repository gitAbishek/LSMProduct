import http from './http';

class API {
	uri: string;

	constructor(uri: string) {
		this.uri = `${uri}/`;
	}

	async list(query?: any) {
		return http({
			path: query ? `${this.uri}?${query}` : this.uri,
			method: 'get',
		}).then((res: any) => res);
	}

	async get(id: number) {
		return http({
			path: this.uri + id,
			method: 'get',
		}).then((res: any) => res);
	}

	async store(data: any) {
		return http({
			path: this.uri,
			method: 'post',
			data: data,
		}).then((res: any) => res);
	}

	async update(id: number, data: any) {
		return http({
			path: this.uri + id,
			method: 'post',
			headers: {
				'x-http-method-override': 'PUT',
			},
			data: data,
		}).then((res: any) => res);
	}

	async delete(id: number, params?: any) {
		return http({
			path: params ? `${this.uri}${id}?${params}` : this.uri + id,
			method: 'post',
			headers: {
				'x-http-method-override': 'DELETE',
			},
		}).then((res: any) => res);
	}

	async start(id: number) {
		return http({
			path: this.uri + 'start_quiz',
			data: { id },
			method: 'post',
		}).then((res: any) => res);
	}

	async check(id: number, data: any) {
		return http({
			path: this.uri + 'check_answers',
			data: { id, data },
			method: 'post',
		}).then((res: any) => res);
	}
}

export default API;
