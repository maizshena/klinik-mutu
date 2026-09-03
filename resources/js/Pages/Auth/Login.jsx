import { useState } from "react";
import { Head, Link, useForm } from "@inertiajs/react";
import { Eye, EyeOff, Store, ShieldCheck, Sparkles } from "lucide-react";
import GuestLayout from "@/Layouts/GuestLayout";
import Card from "@/Components/UI/Card";
import Button from "@/Components/UI/Button";
import InputLabel from "@/Components/UI/InputLabel";
import TextInput from "@/Components/UI/TextInput";

// copy changes depending on which account type is selected, keeps the same
// underlying /login endpoint — role is resolved server-side from the account itself
const ACCOUNT_TYPES = {
  pelaku_usaha: {
    label: "Pengguna Layanan",
    description: "Pelaku usaha dan pemohon",
    icon: Store,
    emailLabel: "Email Pengguna",
    emailPlaceholder: "nama@email.com",
    submitLabel: "Masuk sebagai Pengguna Layanan",
  },
  petugas: {
    label: "Admin & Pembina Mutu",
    description: "Petugas pusat dan daerah",
    icon: ShieldCheck,
    emailLabel: "Email Petugas",
    emailPlaceholder: "nama@klinikmutu.id",
    submitLabel: "Masuk sebagai Admin atau Pembina Mutu",
  },
};

export default function Login() {
  const [accountType, setAccountType] = useState("pelaku_usaha");
  const [showPassword, setShowPassword] = useState(false);
  const copy = ACCOUNT_TYPES[accountType];

  const { data, setData, post, processing, errors } = useForm({
    email: "",
    password: "",
    remember: false,
  });

  function submit(e) {
    e.preventDefault();
    post(route("login"));
  }

  return (
    <GuestLayout>
      <Head title="Masuk" />

      <div className="text-center mb-8">
        <span className="announcement-pill mb-4">
          <Sparkles className="h-3.5 w-3.5" />
          Portal Masuk Resmi
        </span>
        <h1 className="text-2xl font-bold text-brand-dark">Masuk ke akun kamu</h1>
        <p className="text-sm text-brand-text/60 mt-1.5">
          Pilih jenis akun, lalu masukkan data yang telah terdaftar.
        </p>
      </div>

      {/* account type segmented control */}
      <div className="grid grid-cols-2 gap-3 mb-6">
        {Object.entries(ACCOUNT_TYPES).map(([key, value]) => {
          const Icon = value.icon;
          const active = accountType === key;

          return (
            <button
              key={key}
              type="button"
              onClick={() => setAccountType(key)}
              className={`focus-ring flex flex-col items-start gap-2 rounded-2xl border p-4 text-left transition-colors
                ${active ? "border-brand-secondary bg-brand-secondary/5" : "border-slate-200 bg-white hover:bg-brand-bg"}`}
            >
              <Icon className={`h-5 w-5 ${active ? "text-brand-primary" : "text-brand-text/40"}`} />
              <div>
                <p className={`text-sm font-semibold ${active ? "text-brand-dark" : "text-brand-text"}`}>
                  {value.label}
                </p>
                <p className="text-xs text-brand-text/60 mt-0.5">{value.description}</p>
              </div>
            </button>
          );
        })}
      </div>

      <Card>
        <form onSubmit={submit} className="space-y-5">
          <div>
            <InputLabel htmlFor="email" value={copy.emailLabel} required />
            <TextInput
              id="email"
              type="email"
              value={data.email}
              placeholder={copy.emailPlaceholder}
              onChange={(e) => setData("email", e.target.value)}
              error={errors.email}
              isFocused
              required
            />
          </div>

          <div>
            <div className="flex items-center justify-between mb-1.5">
              <InputLabel htmlFor="password" value="Password" required className="mb-0" />
              <Link href={route("password.request")} className="text-xs font-medium text-brand-primary hover:text-brand-secondary">
                Lupa password?
              </Link>
            </div>

            <div className="relative">
              <TextInput
                id="password"
                type={showPassword ? "text" : "password"}
                value={data.password}
                onChange={(e) => setData("password", e.target.value)}
                error={errors.password}
                className="pr-10"
                required
              />
              <button
                type="button"
                onClick={() => setShowPassword((prev) => !prev)}
                className="focus-ring absolute right-3 top-1/2 -translate-y-1/2 text-brand-text/40 hover:text-brand-text"
                tabIndex={-1}
              >
                {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
              </button>
            </div>
          </div>

          <label className="flex items-center gap-2 text-sm text-brand-text/70">
            <input
              type="checkbox"
              checked={data.remember}
              onChange={(e) => setData("remember", e.target.checked)}
              className="focus-ring rounded border-slate-300 text-brand-primary"
            />
            Ingat saya
          </label>

          <Button type="submit" loading={processing} className="w-full">
            {copy.submitLabel}
          </Button>
        </form>
      </Card>

      {accountType === "petugas" ? (
        <div className="mt-6 text-center text-sm text-brand-text/60 space-y-1.5">
          <p>Belum memiliki akun petugas?</p>
          <div className="flex items-center justify-center gap-4">
            <Link href={route("activation.admin")} className="font-medium text-brand-primary hover:text-brand-secondary">
              Aktivasi Admin
            </Link>
            <span className="text-slate-300">·</span>
            <Link href={route("activation.petugas")} className="font-medium text-brand-primary hover:text-brand-secondary">
              Aktivasi Pembina
            </Link>
          </div>
        </div>
      ) : (
        <p className="mt-6 text-center text-sm text-brand-text/60">
          Belum punya akun?{" "}
          <Link href={route("register")} className="font-medium text-brand-primary hover:text-brand-secondary">
            Daftar sekarang
          </Link>
        </p>
      )}
    </GuestLayout>
  );
}