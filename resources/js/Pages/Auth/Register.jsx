import { useMemo, useState } from "react";
import { Head, Link, useForm } from "@inertiajs/react";
import axios from "axios";
import { Search, CheckCircle2, AlertTriangle, FileSearch, FilePlus2, Sparkles } from "lucide-react";
import GuestLayout from "@/Layouts/GuestLayout";
import Card, { CardHeader } from "@/Components/UI/Card";
import Button from "@/Components/UI/Button";
import InputLabel from "@/Components/UI/InputLabel";
import TextInput from "@/Components/UI/TextInput";
import Select from "@/Components/UI/Select";
import Textarea from "@/Components/UI/Textarea";
import Spinner from "@/Components/UI/Spinner";

const JALUR_OPTIONS = [
  { key: "kusuka", label: "Sudah Punya KUSUKA", description: "Klaim data usaha terdaftar", icon: FileSearch },
  { key: "manual", label: "Belum Punya KUSUKA", description: "Isi data usaha secara manual", icon: FilePlus2 },
];

export default function Register({ provinces, regencies }) {
  const [jalur, setJalur] = useState("kusuka");
  const [searching, setSearching] = useState(false);
  const [searchResult, setSearchResult] = useState(null);

  const { data, setData, post, processing, errors, transform } = useForm({
    nama_lengkap: "",
    email: "",
    whatsapp: "",
    password: "",
    password_confirmation: "",
    no_kusuka: "",
    catatan_klaim: "",
    nama_usaha: "",
    provinsi_id: "",
    kabupaten_id: "",
    alamat: "",
    komoditas: "",
  });

  const filteredRegencies = useMemo(
    () => regencies.filter((regency) => String(regency.parent_id) === String(data.provinsi_id)),
    [regencies, data.provinsi_id]
  );

  async function searchKusuka() {
    if (!data.no_kusuka) return;

    setSearching(true);
    setSearchResult(null);

    try {
      const response = await axios.get(route("kusuka.search"), { params: { no_kusuka: data.no_kusuka } });
      setSearchResult(response.data);
    } catch (error) {
      setSearchResult(error.response?.data ?? { found: false, message: "Terjadi kesalahan saat mencari data." });
    } finally {
      setSearching(false);
    }
  }

  function submit(e) {
    e.preventDefault();
    transform((formData) => ({ ...formData, jalur_pendaftaran: jalur }));
    post(route("register"));
  }

  return (
    <GuestLayout>
      <Head title="Daftar Akun" />

      <div className="text-center mb-8">
        <span className="announcement-pill mb-4">
          <Sparkles className="h-3.5 w-3.5" />
          Pendaftaran Pelaku Usaha
        </span>
        <h1 className="text-2xl font-bold text-brand-dark">Daftar sebagai Pelaku Usaha</h1>
        <p className="text-sm text-brand-text/60 mt-1.5">
          Pilih jalur pendaftaran sesuai status KUSUKA usaha kamu.
        </p>
      </div>

      <div className="grid grid-cols-2 gap-3 mb-6">
        {JALUR_OPTIONS.map((option) => {
          const Icon = option.icon;
          const active = jalur === option.key;

          return (
            <button
              key={option.key}
              type="button"
              onClick={() => setJalur(option.key)}
              className={`focus-ring flex flex-col items-start gap-2 rounded-2xl border p-4 text-left transition-colors
                ${active ? "border-brand-secondary bg-brand-secondary/5" : "border-slate-200 bg-white hover:bg-brand-bg"}`}
            >
              <Icon className={`h-5 w-5 ${active ? "text-brand-primary" : "text-brand-text/40"}`} />
              <div>
                <p className={`text-sm font-semibold ${active ? "text-brand-dark" : "text-brand-text"}`}>
                  {option.label}
                </p>
                <p className="text-xs text-brand-text/60 mt-0.5">{option.description}</p>
              </div>
            </button>
          );
        })}
      </div>

      <Card>
        <form onSubmit={submit} className="space-y-6">
          <div>
            <CardHeader title="Data Diri" description="Informasi akun untuk login ke Klinik Mutu" />

            <div className="space-y-4">
              <div>
                <InputLabel htmlFor="nama_lengkap" value="Nama Lengkap" required />
                <TextInput
                  id="nama_lengkap"
                  value={data.nama_lengkap}
                  onChange={(e) => setData("nama_lengkap", e.target.value)}
                  error={errors.nama_lengkap}
                  required
                />
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <InputLabel htmlFor="email" value="Email" required />
                  <TextInput
                    id="email"
                    type="email"
                    value={data.email}
                    onChange={(e) => setData("email", e.target.value)}
                    error={errors.email}
                    required
                  />
                </div>
                <div>
                  <InputLabel htmlFor="whatsapp" value="Nomor WhatsApp" required />
                  <TextInput
                    id="whatsapp"
                    value={data.whatsapp}
                    onChange={(e) => setData("whatsapp", e.target.value)}
                    error={errors.whatsapp}
                    placeholder="08xxxxxxxxxx"
                    required
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <InputLabel htmlFor="password" value="Password" required />
                  <TextInput
                    id="password"
                    type="password"
                    value={data.password}
                    onChange={(e) => setData("password", e.target.value)}
                    error={errors.password}
                    required
                  />
                </div>
                <div>
                  <InputLabel htmlFor="password_confirmation" value="Konfirmasi Password" required />
                  <TextInput
                    id="password_confirmation"
                    type="password"
                    value={data.password_confirmation}
                    onChange={(e) => setData("password_confirmation", e.target.value)}
                    required
                  />
                </div>
              </div>
            </div>
          </div>

          <hr className="border-slate-200" />

          {jalur === "kusuka" ? (
            <div>
              <CardHeader title="Klaim Data KUSUKA" description="Cari data usahamu di master data KUSUKA" />

              <div className="space-y-4">
                <div>
                  <InputLabel htmlFor="no_kusuka" value="Nomor KUSUKA" required />
                  <div className="flex gap-2">
                    <TextInput
                      id="no_kusuka"
                      value={data.no_kusuka}
                      onChange={(e) => {
                        setData("no_kusuka", e.target.value);
                        setSearchResult(null);
                      }}
                      error={errors.no_kusuka}
                      placeholder="Masukkan nomor KUSUKA"
                      className="flex-1"
                      required
                    />
                    <Button
                      type="button"
                      variant="secondary"
                      onClick={searchKusuka}
                      disabled={searching || !data.no_kusuka}
                      icon={searching ? undefined : Search}
                    >
                      {searching ? <Spinner className="h-4 w-4 text-brand-text/60" /> : "Cari"}
                    </Button>
                  </div>
                </div>

                {searchResult && (
                  <div
                    className={`flex items-start gap-3 rounded-xl border px-4 py-3 text-sm
                      ${searchResult.found ? "border-emerald-200 bg-emerald-50 text-emerald-800" : "border-amber-200 bg-amber-50 text-amber-800"}`}
                  >
                    {searchResult.found ? (
                      <CheckCircle2 className="h-4.5 w-4.5 flex-shrink-0 mt-0.5" />
                    ) : (
                      <AlertTriangle className="h-4.5 w-4.5 flex-shrink-0 mt-0.5" />
                    )}
                    <div>
                      {searchResult.found ? (
                        <>
                          <p className="font-semibold">Data ditemukan</p>
                          <p className="mt-0.5">
                            {searchResult.data.nama_pelaku} — {searchResult.data.nama_usaha ?? "-"}
                          </p>
                          <p className="text-xs mt-0.5 opacity-80">
                            {searchResult.data.kabupaten_name}, {searchResult.data.provinsi_name}
                          </p>
                          {searchResult.sudah_terhubung && (
                            <p className="mt-2 text-xs font-semibold">
                              Data ini sudah terhubung ke akun lain yang terverifikasi.
                            </p>
                          )}
                        </>
                      ) : (
                        <p>{searchResult.message}</p>
                      )}
                    </div>
                  </div>
                )}

                <div>
                  <InputLabel htmlFor="catatan_klaim" value="Catatan Klaim (opsional)" />
                  <Textarea
                    id="catatan_klaim"
                    value={data.catatan_klaim}
                    onChange={(e) => setData("catatan_klaim", e.target.value)}
                    placeholder="Jelaskan hubungan kamu dengan usaha ini, mis. pemilik / pengelola"
                    rows={3}
                  />
                </div>

                <p className="text-xs text-brand-text/50">
                  Klaim KUSUKA akan diverifikasi oleh admin sebelum akun aktif sepenuhnya.
                </p>
              </div>
            </div>
          ) : (
            <div>
              <CardHeader title="Data Usaha" description="Isi informasi usaha secara manual" />

              <div className="space-y-4">
                <div>
                  <InputLabel htmlFor="nama_usaha" value="Nama Usaha" required />
                  <TextInput
                    id="nama_usaha"
                    value={data.nama_usaha}
                    onChange={(e) => setData("nama_usaha", e.target.value)}
                    error={errors.nama_usaha}
                    required
                  />
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <InputLabel htmlFor="provinsi_id" value="Provinsi" required />
                    <Select
                      id="provinsi_id"
                      value={data.provinsi_id}
                      onChange={(e) => {
                        setData("provinsi_id", e.target.value);
                        setData("kabupaten_id", "");
                      }}
                      error={errors.provinsi_id}
                      required
                    >
                      <option value="">Pilih provinsi</option>
                      {provinces.map((province) => (
                        <option key={province.id} value={province.id}>
                          {province.nama_wilayah}
                        </option>
                      ))}
                    </Select>
                  </div>

                  <div>
                    <InputLabel htmlFor="kabupaten_id" value="Kabupaten/Kota" required />
                    <Select
                      id="kabupaten_id"
                      value={data.kabupaten_id}
                      onChange={(e) => setData("kabupaten_id", e.target.value)}
                      error={errors.kabupaten_id}
                      disabled={!data.provinsi_id}
                      required
                    >
                      <option value="">Pilih kabupaten/kota</option>
                      {filteredRegencies.map((regency) => (
                        <option key={regency.id} value={regency.id}>
                          {regency.nama_wilayah}
                        </option>
                      ))}
                    </Select>
                  </div>
                </div>

                <div>
                  <InputLabel htmlFor="alamat" value="Alamat Usaha" />
                  <Textarea
                    id="alamat"
                    value={data.alamat}
                    onChange={(e) => setData("alamat", e.target.value)}
                    rows={2}
                  />
                </div>

                <div>
                  <InputLabel htmlFor="komoditas" value="Komoditas (opsional)" />
                  <TextInput
                    id="komoditas"
                    value={data.komoditas}
                    onChange={(e) => setData("komoditas", e.target.value)}
                    placeholder="mis. ikan tuna, udang, rumput laut"
                  />
                </div>
              </div>
            </div>
          )}

          <Button type="submit" loading={processing} className="w-full">
            Daftar Sekarang
          </Button>
        </form>
      </Card>

      <p className="mt-6 text-center text-sm text-brand-text/60">
        Sudah punya akun?{" "}
        <Link href={route("login")} className="font-medium text-brand-primary hover:text-brand-secondary">
          Masuk di sini
        </Link>
      </p>
    </GuestLayout>
  );
}