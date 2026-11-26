import { render, screen } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import Login from './components/Login';

test('renders login page with send OTP action', () => {
  render(
    <AuthProvider>
      <MemoryRouter>
        <Login />
      </MemoryRouter>
    </AuthProvider>
  );
  expect(screen.getByRole('button', { name: /send otp/i })).toBeInTheDocument();
});
