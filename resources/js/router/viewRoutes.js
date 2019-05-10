import Home from '../views/Home'
import Users from '../views/users/Users'
import Employments from '../views/users/employments/Employments'
import Positions from '../views/positions/Positions'
import Departments from '../views/departments/Departments'
import Branches from '../views/branches/Branches'
import BScheds from '../views/branches/schedules/Schedules'
import Regions from '../views/regions/Regions'
import Roles from '../views/roles/Roles'
import Permissions from '../views/permissions/Permissions'

const viewRoutes = [
  {
    path: '/',
    component: Home,
    name: 'home',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/users',
    component: Users,
    name: 'users',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/user-employments',
    component: Employments,
    name: 'user.employments',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/positions',
    component: Positions,
    name: 'positions',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/departments',
    component: Departments,
    name: 'departments',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/branches',
    component: Branches,
    name: 'branches',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/branch-schedules',
    component: BScheds,
    name: 'branch.schedules',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/regions',
    component: Regions,
    name: 'regions',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/permissions',
    component: Permissions,
    name: 'permissions',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/roles',
    component: Roles,
    name: 'roles',
    meta: {
      requiresAuth: true
    }
  },
]

export default viewRoutes