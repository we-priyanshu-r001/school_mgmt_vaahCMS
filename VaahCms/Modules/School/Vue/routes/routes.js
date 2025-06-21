let routes= [];

import dashboard from "./vue-routes-dashboard";
import teachers from "./vue-routes-teachers";
import students from "./vue-routes-students";
import batches from "./vue-routes-batches";

routes = routes.concat(dashboard);
routes = routes.concat(teachers);
routes = routes.concat(students);
routes = routes.concat(batches);

export default routes;
