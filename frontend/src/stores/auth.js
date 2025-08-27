import { defineStore } from 'pinia';
import Cookies from "js-cookie";
import http from '@/utils/http.js';

export const useAuthStore = defineStore('auth_identia', {
    state: () => ({
        authUser: {},
        authToken: null,
        authErrors: {},
        authRoles: [],
        authPermissions: [],
        authEmpresa: {},
    }),
    getters: {
        user: (state) => state.authUser,
        token: (state) => state.authToken,
        errors: (state) => state.authErrors,
        roles: (state) => state.authRoles,
        permissions: (state) => state.authPermissions,
        empresa: (state) => state.authEmpresa,
    },
    actions: {
        async login(form) {
            await http.post('/auth/login', form).then(
                (res) => {
                    this.authUser = res.data.user;
                    this.authToken = 'Bearer ' + res.data.token;
                    this.authErrors = {};
                    this.authRoles = res.data.roles;
                    this.authPermissions = res.data.permissions;
                    this.authEmpresa = res.data.empresa;
                    Cookies.remove('authToken');
                    Cookies.set('authToken', 'Bearer ' + res.data.token);
                    window.location.reload();
                    this.router.push('/dashboard');
                }
            ).catch(
                (errors) => {
                    this.authErrors = errors.response.data.errors;
                }
            );
        },
        async logout() {
            try {
                await http.get('/auth/logout', {
                    params: {
                        authId: this.authUser.id
                    }
                });
                this.authUser = {};
                this.authToken = null;
                this.authRoles = [];
                this.authPermissions = [];
                this.authEmpresa = {};
                Cookies.remove('authToken');
                this.router.push('/login');
            } catch (errors) {
                console.error(errors);
            }
        },
        async validateToken() {
            const token = this.authToken;

            if (!token) return;

            try {
                const base64Url = token.split('.')[1];
                const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                const jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function (c) {
                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                }).join(''));

                const res = await http.get('/auth/validate-token', {
                    params: {
                        authTokenDecoded: JSON.parse(jsonPayload)
                    }
                });

                if (res.data.status === false) {
                    this.authUser = {};
                    this.authToken = null;
                    this.authRoles = [];
                    this.authPermissions = [];
                    this.authEmpresa = {};
                    Cookies.remove('authToken');
                    this.router.push('/');
                }
            } catch (error) {
                console.error(error);
            }
        },
        async ssoLogin(form) {
            try {
                const res = await http.post('/auth/sso/login', {
                    email: form.email,
                    password: form.password,
                    client_id: import.meta.env.VITE_IDP_CLIENT_ID,
                    redirect_uri: import.meta.env.VITE_IDP_REDIRECT_URI,
                });

                if (res.data.redirect_url) {
                    window.location.href = res.data.redirect_url;
                }
            } catch (errors) {
                this.authErrors = errors.response.data.errors;
            }
        },
    },
    persist: true
});