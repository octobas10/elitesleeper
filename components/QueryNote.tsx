"use client";

import { useEffect, useState } from "react";

export function QueryNote({ messages }: { messages: Record<string, string> }) {
  const [text, setText] = useState("");
  const [tone, setTone] = useState<"bad" | "ok">("bad");

  const encoded = JSON.stringify(messages);

  useEffect(() => {
    const map = JSON.parse(encoded) as Record<string, string>;
    const params = new URLSearchParams(window.location.search);
    const error = params.get("error");
    const sent = params.get("sent");
    if (error && map[error]) {
      setTone("bad");
      const why = params.get("why")?.trim();
      setText(why ? `${map[error]} (${why})` : map[error]);
      return;
    }
    if (sent && map[sent]) {
      setTone("ok");
      setText(map[sent]);
    }
  }, [encoded]);

  if (!text) return null;
  return (
    <p className={tone === "ok" ? "note note-ok" : "note note-bad"} role="status">
      {text}
    </p>
  );
}
