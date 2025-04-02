<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <a href="{{ route('home') }}" class="brand-link">
            <img
              <img src="{{ Vite::asset('resources/images/img/AdminLTELogo.png') }}"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <span class="brand-text fw-light">APPZ</span>
          </a>
        </div>
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >
              <li class="nav-item menu-open">
                <a href="{{ route('home') }}" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
                <ul class="nav nav-treeview">
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-pencil-square"></i>
                  <p>
                    Administração
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route ('users.index') }}" class="nav-link">
                      <i class="nav-icon bi bi-person-gear"></i>
                      <p>Usuarios</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route ('sectors.index') }}" class="nav-link">
                      <i class="nav-icon bi bi-people"></i>
                      <p>Setores</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route ('companies.index') }}" class="nav-link">
                      <i class="nav-icon bi-building-gear"></i>
                      <p>Empresa</p>
                    </a>
                  </li>           
                </ul>
                  <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-gear"></i>
                  <p>
                    Configurar
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route ('macros.index') }}" class="nav-link">
                      <i class="nav-icon bi bi-filter-square"></i>
                      <p>Macros</p>
                    </a>
                  </li>
                 

                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-person"></i>
                  <p>Configurar</p>
                </a>
              </li>

            
            </ul>

            
          </nav>
        </div>
      </aside>