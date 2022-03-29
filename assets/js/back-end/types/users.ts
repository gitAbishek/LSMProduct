import { UserSchema } from '../schemas';

export interface UsersApiResponse {
	data: UserSchema[];
	meta: {
		current_page: number;
		pages: number;
		per_page: number;
		total: number;
	};
}
