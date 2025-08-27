import http from "@/utils/http.js";

class EmpresaService {
    static async updateEmpresaInformation(row) {
        return (await http.post(`/api/update-empresa-information/`, row)).data;
    };

    static async updateEmpresaLogo(row) {
        var formData = new FormData();
        formData.append(`logo`, row.logo);
        return (await http.post(`/api/update-empresa-logo/`, formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        })).data;
    };

    static async deleteEmpresaLogo() {
        return (await http.get(`/api/delete-empresa-logo/`)).data;
    };
}

export default EmpresaService;