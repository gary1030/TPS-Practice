const API_HOST = "http://localhost:3000";
const API_GET_MEMBERS = `${API_HOST}/members`;
const API_GET_GOODS = `${API_HOST}/goods`;
const API_GET_TRANS = `${API_HOST}/transaction`;

async function fetchMembers() {
  //console.log(API_GET_MEMBERS);
  const res = await fetch(API_GET_MEMBERS);
  //console.log(res);
  const data = await res.json();
  return data;
}

async function fetchGoods() {
  const res = await fetch(API_GET_GOODS);
  const data = await res.json();
  return data;
}

async function fetchTrans() {
  const res = await fetch(API_GET_TRANS);
  const data = await res.json();
  return data;
}

export { fetchMembers, fetchGoods, fetchTrans };
