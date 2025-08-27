import http from "@/utils/http.js";

class SistemasService {
    static async getData(params) {
        return (await http.get(`/api/sistemas`, params)).data;
    }

    static async get(id) {
        return (await http.get(`/api/sistemas/${id}`)).data;
    }

    static async save(row) {
        if (row.id === undefined || row.id === null) {
            return (await http.post(`/api/sistemas`, row)).data;
        } else {
            return (await http.put(`/api/sistemas/${row.id}`, row)).data;
        }
    }

    static async delete(id) {
        return (await http.delete(`/api/sistemas/${id}`));
    }

    static async changeAccessUserSistema(row) {
        return (await http.post(`/api/sistemas-change-access-user-sistema`, row)).data;
    }
}

export default SistemasService;
