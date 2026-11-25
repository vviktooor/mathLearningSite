// Page behaviour: learn mode (endless practice) and test mode (10 tasks, then save the score). Author: WR
import { TEST_LENGTH, check, task } from "./quiz.js";

const root = document.querySelector("[data-op]");
const { op, mode } = root.dataset;
const text = JSON.parse(document.getElementById("i18n").textContent);
const $ = (id) => document.getElementById(id);
let current, done = 0, correct = 0;

function next() {
  current = task(op);
  $("left").textContent = current.left;
  $("sign").textContent = current.sign;
  $("right").textContent = current.right;
  $("answer").value = "";
  $("answer").focus();
  if (mode === "test") $("progress").textContent = text.progress.replace("{done}", done + 1).replace("{total}", TEST_LENGTH);
}

function feedback(kind) {
  const box = $("feedback");
  box.className = `feedback ${kind}`;
  box.textContent = kind === "correct" ? text.correct : kind === "wrong" ? text.wrong.replace("{answer}", current.answer) : text.empty;
}

$("form").addEventListener("submit", (event) => {
  event.preventDefault();
  const result = check($("answer").value, current.answer);
  feedback(result);
  if (result === "empty") return;
  if (mode === "learn") return setTimeout(next, result === "correct" ? 700 : 1600);
  done += 1;
  if (result === "correct") correct += 1;
  if (done < TEST_LENGTH) return next();
  $("form").hidden = true;
  $("progress").hidden = true;
  $("finish").hidden = false;
  $("score").textContent = text.score.replace("{correct}", correct).replace("{total}", TEST_LENGTH);
  $("points").value = correct;
});

next();
