// Quiz logic shared by the browser and the Node tests. Author: WR
export const OPERATIONS = {
  add: { sign: "+", make: (a, b) => [a, b, a + b] },
  sub: { sign: "−", make: (a, b) => [Math.max(a, b), Math.min(a, b), Math.max(a, b) - Math.min(a, b)] },
  mul: { sign: "×", make: (a, b) => [a % 11, b % 11, (a % 11) * (b % 11)] },
  // division always has a whole-number result: build it from a product
  div: { sign: "÷", make: (a, b) => [(a % 10 + 1) * (b % 10 + 1), a % 10 + 1, b % 10 + 1] },
};
export const TEST_LENGTH = 10;

export function task(op, random = Math.random) {
  const pick = () => Math.floor(random() * 100) + 1;
  const [left, right, answer] = OPERATIONS[op].make(pick(), pick());
  return { left, right, answer, sign: OPERATIONS[op].sign };
}

// "" means no answer yet; otherwise compare as a whole number
export function check(input, answer) {
  const value = String(input).trim();
  if (value === "") return "empty";
  return /^-?\d+$/.test(value) && Number(value) === answer ? "correct" : "wrong";
}
