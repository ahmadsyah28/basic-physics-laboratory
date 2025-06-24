<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestingRequestFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'sample_description' => 'required|string|max:2000',
            'test_requirements' => 'required|string|max:2000',
            'expected_date' => 'required|date|after_or_equal:today',
            'testing_services' => 'required|array|min:1',
            'testing_services.*' => 'exists:jenis_pengujian,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'organization.required' => 'Institusi/Organisasi wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'sample_description.required' => 'Deskripsi sampel wajib diisi.',
            'test_requirements.required' => 'Kebutuhan pengujian wajib diisi.',
            'expected_date.required' => 'Tanggal diharapkan wajib diisi.',
            'expected_date.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini.',
            'testing_services.required' => 'Pilih minimal satu layanan pengujian.',
            'testing_services.min' => 'Pilih minimal satu layanan pengujian.',
            'testing_services.*.exists' => 'Layanan pengujian yang dipilih tidak valid.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama lengkap',
            'organization' => 'institusi/organisasi',
            'email' => 'email',
            'phone' => 'nomor telepon',
            'sample_description' => 'deskripsi sampel',
            'test_requirements' => 'kebutuhan pengujian',
            'expected_date' => 'tanggal diharapkan',
            'testing_services' => 'layanan pengujian'
        ];
    }
}
