"use client";

function digits(value: string): string {
  return value.replace(/\D+/g, "");
}

export function NmiPayForm({ formUrl, total }: { formUrl: string; total: string }) {
  return (
    <form
      className="form"
      method="POST"
      action={formUrl}
      acceptCharset="UTF-8"
      autoComplete="on"
      encType="application/x-www-form-urlencoded"
      noValidate
      onSubmit={(event) => {
        event.preventDefault();
        const form = event.currentTarget;
        const number = digits((form.elements.namedItem("billing-cc-number") as HTMLInputElement)?.value ?? "");
        const exp = digits((form.elements.namedItem("billing-cc-exp") as HTMLInputElement)?.value ?? "").slice(0, 4);
        const cvv = digits((form.elements.namedItem("billing-cvv") as HTMLInputElement)?.value ?? "");

        if (number.length < 13 || exp.length !== 4 || cvv.length < 3) {
          return;
        }

        const native = document.createElement("form");
        native.method = "POST";
        native.action = formUrl;
        native.acceptCharset = "UTF-8";
        native.style.display = "none";

        const fields: Record<string, string> = {
          "billing-cc-number": number,
          "billing-cc-exp": exp,
          "billing-cvv": cvv,
          "cc-number": number,
          ccnumber: number,
          "cc-exp": exp,
          ccexp: exp,
          cvv,
        };
        for (const [name, value] of Object.entries(fields)) {
          const input = document.createElement("input");
          input.name = name;
          input.value = value;
          native.appendChild(input);
        }

        document.body.appendChild(native);
        native.submit();
      }}
    >
      <div className="form-grid">
        <label className="span-2">
          Number
          <input name="billing-cc-number" inputMode="numeric" autoComplete="cc-number" required maxLength={19} />
        </label>
        <label>
          Expiration (MMYY)
          <input
            name="billing-cc-exp"
            inputMode="numeric"
            autoComplete="cc-exp"
            required
            maxLength={5}
            placeholder="1228"
          />
        </label>
        <label>
          Security code
          <input name="billing-cvv" inputMode="numeric" autoComplete="cc-csc" required maxLength={4} />
        </label>
      </div>
      <button className="btn btn-wide" type="submit">
        Pay ${total}
      </button>
      <p className="fine">
        Payment details go to the processor. By paying you agree to the <a href="/terms/">terms</a> and{" "}
        <a href="/privacy/">privacy policy</a>.
      </p>
    </form>
  );
}
