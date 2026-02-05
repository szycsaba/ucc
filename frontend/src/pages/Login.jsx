import Container from "../components/ui/Container";
import TextInput from "../components/ui/TextInput";
import FormError from "../components/ui/FormError";
import Button from "../components/ui/Button";

function Login({ pending }) {
  return (
    <Container size="sm" className="py-10">
      <h1 className="text-2xl font-semibold">Login</h1>

      <form noValidate className="mt-6 space-y-3">
        <TextInput
          type="email"
          placeholder="E-mail"
          autoComplete="email"
          required
        />

        <TextInput
          type="password"
          placeholder="Jelszó"
          autoComplete="current-password"
          required
        />

        {/* <FormError message={error} /> */}

        <Button type="submit" fullWidth>
          Login
        </Button>
      </form>
    </Container>
  );
}

export default Login;
