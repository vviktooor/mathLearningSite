import assert from "node:assert/strict";
import { test } from "node:test";
import { OPERATIONS, check, task } from "../public/assets/quiz.js";

const seq = (...values) => { let i = 0; return () => values[i++ % values.length]; };

test("every operation gives a correct, non-negative whole answer", () => {
  for (const op of Object.keys(OPERATIONS)) {
    for (let i = 0; i < 500; i++) {
      const { left, right, answer } = task(op);
      const real = { add: left + right, sub: left - right, mul: left * right, div: left / right }[op];
      assert.equal(answer, real, op);
      assert.ok(Number.isInteger(answer) && answer >= 0, op);
    }
  }
});

test("subtraction never goes below zero", () => {
  const t = task("sub", seq(0.05, 0.9));
  assert.ok(t.left >= t.right);
});

test("check handles empty, wrong, correct and junk input", () => {
  assert.equal(check("  ", 4), "empty");
  assert.equal(check("4", 4), "correct");
  assert.equal(check(" 4 ", 4), "correct");
  assert.equal(check("5", 4), "wrong");
  assert.equal(check("4abc", 4), "wrong");
});
