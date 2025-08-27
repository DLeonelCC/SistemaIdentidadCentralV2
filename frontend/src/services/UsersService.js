import http from "@/utils/http.js";

class UsersService {
    static async getData(params) {
        return (await http.get(`/api/users/`, params)).data;
    }

    static async get(id) {
        return (await http.get(`/api/users/${id}`)).data;
    }

    static async getByDni(num_doc) {
        return (await http.get(`/api/users/user-by-dni/${num_doc}`)).data;
    }

    static async save(row) {
        if (row.id === undefined || row.id === null) {
            return (await http.post(`/api/users/`, row)).data;
        } else {
            return (await http.put(`/api/users/${row.id}/`, row)).data;
        }
    }

    static async delete(id) {
        return (await http.delete(`/api/users/${id}/`));
    }

    static async getRoles(params) {
        return (await http.get(`/api/users-roles`, params)).data;
    }

    static async addRole(row) {
        return (await http.post(`/api/users-roles-add`, row)).data;
    }

    static async deleteRole(row) {
        return (await http.post(`/api/users-roles-delete`, row));
    }

    static async getTenants(params) {
        return (await http.get(`/api/users-tenants`, params)).data;
    }

    static async addTenant(row) {
        return (await http.post(`/api/users-tenants-add`, row)).data;
    }

    static async deleteTenant(id) {
        return (await http.delete(`/api/users-tenants-delete/${id}`));
    }

    static async getSistemas(params) {
        return (await http.get(`/api/users-sistemas`, params)).data;
    }

    static async addSistema(row) {
        return (await http.post(`/api/users-sistemas-add`, row)).data;
    }

    static async deleteSistema(id) {
        return (await http.delete(`/api/users-sistemas-delete/${id}`));
    }

    static async getProyectos(params) {
        return (await http.get(`/api/users-proyectos`, params)).data;
    }

    static async addProyecto(row) {
        return (await http.post(`/api/users-proyectos-add`, row)).data;
    }

    static async deleteProyecto(id) {
        return (await http.delete(`/api/users-proyectos-delete/${id}`));
    }
}

export default UsersService;