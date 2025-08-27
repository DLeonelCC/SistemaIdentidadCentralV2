import http from "@/utils/http.js";

class TenantsService {
    static async getData(params) {
        return (await http.get(`/api/tenants`, params)).data;
    }

    static async get(id) {
        return (await http.get(`/api/tenants/${id}`)).data;
    }

    static async save(row) {
        if (row.id === undefined || row.id === null) {
            return (await http.post(`/api/tenants`, row)).data;
        } else {
            return (await http.put(`/api/tenants/${row.id}`, row)).data;
        }
    }

    static async delete(id) {
        return (await http.delete(`/api/tenants/${id}`));
    }

    static async changeAccessUserTenant(row) {
        return (await http.post(`/api/tenants-change-access-user-tenant`, row)).data;
    }
}

export default TenantsService;
