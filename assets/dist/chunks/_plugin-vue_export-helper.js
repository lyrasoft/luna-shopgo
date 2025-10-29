import { getCurrentInstance as c } from "vue";
function u(t, e) {
  const o = c();
  if (!o)
    return e;
  const n = o.appContext.components;
  return (n[t] || n[a(t)] || n[s(t)]) ?? e;
}
function s(t) {
  return t.replace(/([a-z])([A-Z])/g, "$1-$2").replace(/[\s_]+/g, "-").toLowerCase();
}
function a(t) {
  return t.replace(/(^\w|-\w)/g, (e) => e.replace(/-/, "").toUpperCase());
}
const f = (t, e) => {
  const o = t.__vccOpts || t;
  for (const [n, r] of e)
    o[n] = r;
  return o;
};
export {
  f as _,
  u as r
};
//# sourceMappingURL=_plugin-vue_export-helper.js.map
