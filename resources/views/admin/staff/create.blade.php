@extends("layouts.admin")

@section("title", isset($staff) ? "Edit Staff" : "Tambah Staff")

@section("content")
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-wrap items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">{{ isset($staff) ? "Edit Staff" : "Tambah Staff Baru" }}</h2>
                <a href="{{ route("admin.staff.index") }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ isset($staff) ? route("admin.staff.update", $staff->id) : route("admin.staff.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($staff))
                    @method("PUT")
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" value="{{ isset($staff) ? $staff->nama : old("nama") }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error("nama")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gelar -->
                        <div>
                            <label for="gelar" class="block text-sm font-medium text-gray-700 mb-1">Gelar Akademik</label>
                            <input type="text" name="gelar" id="gelar" value="{{ isset($staff) ? $staff->gelar : old("gelar") }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error("gelar")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                            <select name="jabatan" id="jabatan" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">Pilih Jabatan</option>
                                <option value="ketua_lab" {{ (isset($staff) && $staff->jabatan == "ketua_lab") || old("jabatan") == "ketua_lab" ? "selected" : "" }}>Ketua Lab</option>
                                <option value="laboran" {{ (isset($staff) && $staff->jabatan == "laboran") || old("jabatan") == "laboran" ? "selected" : "" }}>Laboran</option>
                                <option value="tenaga_pengajar" {{ (isset($staff) && $staff->jabatan == "tenaga_pengajar") || old("jabatan") == "tenaga_pengajar" ? "selected" : "" }}>Tenaga Pengajar</option>
                            </select>
                            @error("jabatan")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ isset($staff) ? $staff->email : old("email") }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error("email")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="telepon" id="telepon" value="{{ isset($staff) ? $staff->telepon : old("telepon") }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error("telepon")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Foto -->
                        <div>
                            <label for="foto" class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                            
                            <div class="mt-2 flex items-center">
                                <div class="w-32 h-32 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center mr-4">
                                    @if(isset($staff) && $staff->foto)
                                        <img src="{{ asset("images/staff/" . $staff->foto) }}" alt="{{ $staff->nama }}" id="preview-image" class="w-full h-full object-cover">
                                    @else
                                        <img src="#" alt="Preview" id="preview-image" class="w-full h-full object-cover hidden">
                                        <div id="empty-preview" class="text-gray-400 flex flex-col items-center justify-center">
                                            <i class="fas fa-user text-3xl mb-2"></i>
                                            <span class="text-sm">Tidak ada foto</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    <label class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition cursor-pointer">
                                        <span><i class="fas fa-upload mr-2"></i> Pilih Foto</span>
                                        <input type="file" name="foto" id="foto" class="hidden" accept="image/*">
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2">Format: JPG, JPEG, PNG. Maks: 2MB</p>
                                    @if(isset($staff) && $staff->foto)
                                        <div class="mt-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="remove_foto" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-600">Hapus foto</span>
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @error("foto")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Spesialisasi -->
                        <div>
                            <label for="spesialisasi" class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi</label>
                            <input type="text" name="spesialisasi" id="spesialisasi" value="{{ isset($staff) ? $staff->spesialisasi : old("spesialisasi") }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error("spesialisasi")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pendidikan -->
                        <div>
                            <label for="pendidikan" class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan" id="pendidikan" value="{{ isset($staff) ? $staff->pendidikan : old("pendidikan") }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error("pendidikan")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="1" class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ (isset($staff) && $staff->status) || old("status") == "1" || !isset($staff) ? "checked" : "" }}>
                                    <span class="ml-2 text-sm text-gray-600">Aktif</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="0" class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ (isset($staff) && !$staff->status) || old("status") == "0" ? "checked" : "" }}>
                                    <span class="ml-2 text-sm text-gray-600">Tidak Aktif</span>
                                </label>
                            </div>
                            @error("status")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bio -->
                <div class="mt-6">
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Biografi</label>
                    <textarea name="bio" id="bio" rows="5" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ isset($staff) ? $staff->bio : old("bio") }}</textarea>
                    @error("bio")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-6 text-right">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        {{ isset($staff) ? "Update Staff" : "Simpan Staff" }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section("scripts")
<script>
    $(document).ready(function() {
        // Image preview
        $("#foto").change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $("#preview-image").attr("src", e.target.result).removeClass("hidden");
                    $("#empty-preview").addClass("hidden");
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Checkbox for removing photo
        $("input[name=remove_foto]").change(function() {
            if($(this).is(":checked")) {
                $("#preview-image").addClass("hidden");
                $("#empty-preview").removeClass("hidden");
            } else {
                $("#preview-image").removeClass("hidden");
                $("#empty-preview").addClass("hidden");
            }
        });
    });
</script>
@endsection
