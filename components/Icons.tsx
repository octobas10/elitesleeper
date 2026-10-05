import type { ReactNode } from "react";

export type IconName =
  | "arrow"
  | "bed"
  | "capsule"
  | "card"
  | "chat"
  | "check"
  | "clock"
  | "leaf"
  | "mail"
  | "menu"
  | "moon"
  | "phone"
  | "pin"
  | "shield"
  | "sun"
  | "truck";

const paths: Record<IconName, ReactNode> = {
  arrow: <path d="M6 14.2h14.2L14.6 8.4l2.3-2.2 9.1 9.8-9.1 9.8-2.3-2.2 5.6-5.8H6v-3.6Z" />,
  bed: (
    <>
      <path d="M3 8h6v16H3V8Z" />
      <path d="M7 14h22v8H7v-8Z" />
      <path d="M9 9h10a2 2 0 0 1 2 2v3H9V9Z" />
      <path d="M26 12h3v10h-3V12Z" />
    </>
  ),
  capsule: (
    <path
      fillRule="evenodd"
      d="M8 8h16a8 8 0 0 1 0 16H8a8 8 0 0 1 0-16Zm7 2.2v11.6h2V10.2h-2Z"
    />
  ),
  card: (
    <path
      fillRule="evenodd"
      d="M4 7.5h24a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-13a2 2 0 0 1 2-2Zm0 4.5h24v3.2H4V12Zm2.2 7.2h7.2v2.4H6.2v-2.4Z"
    />
  ),
  chat: <path d="M5 6.5h22a2 2 0 0 1 2 2v10.5a2 2 0 0 1-2 2H13.2L6.5 27v-6H5a2 2 0 0 1-2-2V8.5a2 2 0 0 1 2-2Z" />,
  check: <path d="M5.5 16.2 12.4 23.4 27 7.6l-3.1-2.9-11.5 12.4-3.8-4-3.1 3.1Z" />,
  clock: (
    <path
      fillRule="evenodd"
      d="M16 3a13 13 0 1 0 0 26 13 13 0 0 0 0-26Zm1.6 6.2v6.2l4.4 2.6-1.6 2.6-6-3.6V9.2h3.2Z"
    />
  ),
  leaf: <path d="M6 25C6 14 13 5 27 5 26 18 16 26 6 25Zm6.2-4.2c3.2-3.4 6.4-8 9.2-12.6" />,
  mail: (
    <path
      fillRule="evenodd"
      d="M4 8h24v16.5H4V8Zm3.2 2.4 8.8 6.4 8.8-6.4v2.6L16 19.2 7.2 13v-2.6Z"
    />
  ),
  menu: (
    <>
      <path d="M4 6.2h24v3.4H4V6.2Z" />
      <path d="M4 14.3h24v3.4H4v-3.4Z" />
      <path d="M4 22.4h24v3.4H4v-3.4Z" />
    </>
  ),
  moon: <path d="M18.2 3.2A12.4 12.4 0 1 0 28.6 18 9.4 9.4 0 0 1 18.2 3.2Z" />,
  phone: (
    <path d="M8.2 3.2h4.4l2.2 5.2-3.4 2.1a13 13 0 0 0 7.1 7.1l2.1-3.4 5.2 2.2v4.4a2.4 2.4 0 0 1-2.6 2.4C12.6 23 5 15.4 4.8 6.2a2.4 2.4 0 0 1 2.4-3h1Z" />
  ),
  pin: (
    <path
      fillRule="evenodd"
      d="M16 2.4c5.2 0 9.4 4.1 9.4 9.2 0 6.8-9.4 17.8-9.4 17.8S6.6 18.4 6.6 11.6C6.6 6.5 10.8 2.4 16 2.4Zm0 5.6a3.4 3.4 0 1 0 .02 0Z"
    />
  ),
  shield: <path d="M16 2.6 27 6.4v8.2c0 6.2-4.2 10.2-11 12.8C9.2 24.8 5 20.8 5 14.6V6.4L16 2.6Zm-3.2 12.2 2.6 2.6 5.4-5.6 2.2 2.1-7.6 7.8-4.8-4.8 2.2-2.1Z" />,
  sun: (
    <>
      <circle cx="16" cy="16" r="6.2" />
      <path d="M13.6 1.2h4.8v6.2h-4.8V1.2Zm0 23.4h4.8v6.2h-4.8v-6.2ZM1.2 13.6h6.2v4.8H1.2v-4.8Zm23.4 0h6.2v4.8h-6.2v-4.8Z" />
      <path d="M5.2 7.4 8.6 4l4.4 4.4-3.4 3.4L5.2 7.4Zm13.2 13.2 3.4-3.4 4.4 4.4-3.4 3.4-4.4-4.4Zm0-13.2 4.4-4.4 3.4 3.4-4.4 4.4-3.4-3.4ZM5.2 24.6l4.4-4.4 3.4 3.4-4.4 4.4-3.4-3.4Z" />
    </>
  ),
  truck: (
    <path
      fillRule="evenodd"
      d="M3 8.5h13.2v8.2h4.2l5.6 4.2v3.2h-2.8a3.3 3.3 0 0 1-6.4 0h-6.2a3.3 3.3 0 0 1-6.4 0H3V8.5Zm5.4 13.6a1.5 1.5 0 1 0 .02 0Zm12.2 0a1.5 1.5 0 1 0 .02 0Z"
    />
  ),
};

export function Icon({ name }: { name: IconName }) {
  return (
    <svg className="icon" viewBox="0 0 32 32" aria-hidden="true">
      {paths[name]}
    </svg>
  );
}
