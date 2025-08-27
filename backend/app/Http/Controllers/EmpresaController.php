<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpresaRequest;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('America/Lima');
    }

    public function getEmpresa()
    {
        return response(Empresa::find(1));
    }

    public function updateEmpresaInformation(EmpresaRequest $request)
    {
        $empresa = Empresa::find(1);
        $empresa->update($request->all());
        return response($empresa);
    }

    public function updateEmpresaLogo(Request $request)
    {
        $empresa = Empresa::find(1);
        $request->validate([
            'logo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        if ($request->hasFile('logo')) {
            $empresa->updateLogo($request->file('logo'));
        }
        return response($empresa);
    }

    public function deleteEmpresaLogo()
    {
        $empresa = Empresa::find(1);
        $empresa->deleteLogo();
        return response($empresa);
    }
}
