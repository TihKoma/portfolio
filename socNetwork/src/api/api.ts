import axios from "axios";
import {UserType} from "../types/types";


export const instance = axios.create({
	withCredentials: true,
	baseURL: 'https:social-network.samuraijs.com/api/1.0/',
	headers: {
		"API-KEY": "ec9377e0-3924-4787-a0cc-baba333fe985"
		// "API-KEY": "706db5af-f834-4e2c-a9dc-bef600d22075"
	}
});

export enum ResultCodesEnum {
	Success = 0,
	Error = 1
};

export enum ResultCodesForCaptchaEnum {
	CaptchaIsRequired = 10
};

export type GetItemsType = {
	items: Array<UserType>,
	totalCount: number,
	error: string | null
};

// instance.get<string>(`auth/me`).then((res) => res.data.toUpperCase());

export type APIResponseType<D = {}, RC = ResultCodesEnum> = {
	data: D,
	messages: Array<string>,
	resultCode: RC
};