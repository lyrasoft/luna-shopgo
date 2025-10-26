import { getCurrentInstance as c } from "vue";
function u(o, t) {
  const e = c();
  if (console.log(e), !e)
    return t;
  console.log(e.appContext.components);
  const n = e.appContext.components;
  return (n[o] || n[p(o)] || n[s(o)]) ?? t;
}
function s(o) {
  return o.replace(/([a-z])([A-Z])/g, "$1-$2").replace(/[\s_]+/g, "-").toLowerCase();
}
function p(o) {
  return o.replace(/(^\w|-\w)/g, (t) => t.replace(/-/, "").toUpperCase());
}
const l = (o, t) => {
  const e = o.__vccOpts || o;
  for (const [n, r] of t)
    e[n] = r;
  return e;
};
export {
  l as _,
  u as r
};
