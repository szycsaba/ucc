import { useState } from "react";
import { Link } from "react-router-dom";

export default function Navbar() {
  const [open, setOpen] = useState(false);

  const links = [
    { label: "Login", href: "/login" },
    { label: "Helpdesk", href: "/helpdesk" },
  ];

  const linkClass =
    "font-bold text-gray-700 hover:text-[#3e489d] transition-colors";

  return (
    <header className="bg-white border-b border-gray-200">
      <nav className="mx-auto max-w-6xl px-4">
        <div className="flex h-20 items-center justify-between gap-4">
          <Link
            to="/"
            className="text-[#3e489d] font-extrabold tracking-wider uppercase text-xl sm:text-2xl whitespace-nowrap"
          >
            Event Organizer
          </Link>

          <div className="hidden md:flex items-center gap-6 whitespace-nowrap">
            <div className="flex items-center gap-4">
              {links.map((l) => (
                <a key={l.href} href={l.href} className={linkClass}>
                  {l.label}
                </a>
              ))}
            </div>
            <span className="font-semibold text-gray-500">Not logged in</span>
          </div>

          <button
            type="button"
            className="md:hidden font-bold text-2xl text-gray-700 px-2"
            onClick={() => setOpen((v) => !v)}
            aria-label="Toggle menu"
            aria-expanded={open}
          >
            {open ? "✕" : "☰"}
          </button>
        </div>

        {open && (
          <div className="md:hidden pb-4 border-t border-gray-100 pt-3">
            <div className="flex flex-col gap-3">
              {links.map((l) => (
                <a
                  key={l.href}
                  href={l.href}
                  className={linkClass}
                  onClick={() => setOpen(false)}
                >
                  {l.label}
                </a>
              ))}
              <span className="font-semibold text-gray-500">Not logged in</span>
            </div>
          </div>
        )}
      </nav>
    </header>
  );
}
