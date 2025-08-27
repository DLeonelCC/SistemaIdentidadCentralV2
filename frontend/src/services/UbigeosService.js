import http from "@/utils/http.js";

class UbigeosService {
    static async getUbigeo(params) {
        return (await http.get("api/ubigeo", params)).data;
    }

    static async getDepartamentos() {
        return (await http.get("api/departamentos")).data;
    }

    static async getProvincias(cod_dep) {
        var lista = (await http.get("api/provincias")).data;
        return lista.filter((x) => x.cod_dep == cod_dep);
    }

    static async getDistritos(cod_dep, cod_prov) {
        var list = (await http.get("api/distritos")).data;
        return list.filter((x) => x.cod_dep == cod_dep && x.cod_prov == cod_prov);
    }
}

export default UbigeosService;