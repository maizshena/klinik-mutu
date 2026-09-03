import { Head, Link } from "@inertiajs/react";
import { KeyRound } from "lucide-react";
import GuestLayout from "@/Layouts/GuestLayout";
import Card from "@/Components/UI/Card";
import Button from "@/Components/UI/Button";

export default function ForgotPassword() {
  return (
    <GuestLayout>
      <Head title="Lupa Password" />
      <Card className="text-center py-10">
        <div className="mx-auto h-12 w-12 rounded-2xl bg-brand-secondary/10 flex items-center justify-center mb-4">
          <KeyRound className="h-5 w-5 text-brand-primary" />
        </div>
        <h1 className="text-lg font-semibold text-brand-dark">Fitur Segera Hadir</h1>
        <p className="text-sm text-brand-text/60 mt-2 max-w-sm mx-auto">
          Reset password mandiri sedang dalam pengembangan. Sementara itu, hubungi admin
          melalui WhatsApp untuk bantuan reset password.
        </p>
        <Link href={route("login")}>
          <Button variant="secondary" className="mt-6">Kembali ke Login</Button>
        </Link>
      </Card>
    </GuestLayout>
  );
}