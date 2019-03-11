clc;
T = [0 0.1];
lam0 = -(47 + 55/60 + 38/3600)/180*pi;
phi0 = -(15 + 51/60 + 49/3600)/180*pi;
% phi0*180/pi
H0 = 1057;
psi0 = 89.7/180*pi;

dT = 0.1;
V0 = 0; Votr = sqrt(59.7); % Votr - скорость отрыва

T1 = 940*Votr/1800;
T = [0:dT:T1]';
e2 = 6.69437999014*10^(-3);
A = 6378137; %м

lam = 0*T + lam0;
%H = 0*T + H0;
roll = 0*T;
pitch = 0*T;
heading = 0*T + psi0;

phi = phi0;

gamma = 0;
TETA = 0;
psi = psi0;
H = H0;
fMatrix = [];

% S = [cos(TETA)*sin(psi) cos(TETA)*cos(psi) sin(TETA);sin(gamma)*cos(psi)-sin(TETA)*cos(gamma)*sin(psi) -sin(TETA)*cos(gamma)*cos(psi)-sin(gamma)*sin(psi) cos(TETA)*cos(gamma);sin(gamma)*sin(TETA)*sin(psi)+cos(gamma)*cos(psi) sin(gamma)*sin(TETA)*cos(psi)-cos(gamma)*sin(psi) -sin(gamma)*cos(TETA)];
Spsi=[sin(psi) cos(psi) 0;
     -cos(psi) sin(psi) 0;
        0       0       1];
   
Steta=[  cos(TETA)  0 sin(TETA) ;
             0       1      0; 
         -sin(TETA) 0 cos(TETA)];
   
Sgamma= [1        0          0;
         0  -sin(gamma)  cos(gamma);
         0  -cos(gamma) -sin(gamma)];
        
S=(Sgamma*Steta*Spsi) ;

% ___________________3 часть_______________________________
    V1 = 0;
    V2 = 0;
    V3 = 0;
    
    phi3 = phi0;
    lam3 = lam0;
    H3 = H0;
% _________________________________________________________

for i = 2:size(T,1)
%  TETAdomik = 0;
  H(i) = H0;
  V = (V0-Votr)/2*cos(pi*T(i-1,1)/T1) + (V0+Votr)/2;
%      V

  Vn = V*cos(psi0)*cos(TETA);
  Ve = V*sin(psi0)*cos(TETA);
  Vu = V*sin(TETA);
  
%   Vu

  V_ = [Ve;Vn;Vu]; %  V_ - вектор V
  Vshtrix = [-sin(psi0)*pi/T1*(V0-Votr)/2*sin(pi/T1*T(i-1,1));-cos(psi0)*pi/T1*(V0-Votr)/2*sin(pi/T1*T(i-1,1));0]; %  Vshtrix - вектор V'

  Rn = A*(1-e2)/((sqrt(1-e2*(sin(phi(i-1,1)))^2))^3);
   
%   V*cos(psi0)/(Rn+H0)
  phi(i,1) = phi(i-1,1) + Vn*dT/(Rn+H0);
  
  Re = A/sqrt(1-e2*(sin(phi(i-1,1)))^2);
  lam(i,1) = lam(i-1,1) + Ve*dT/((Re+H0)*cos(phi(i-1,1)));
 
  TETAdomik = [0 Ve*tan(phi(i,1))/(Re+H(i)) -Ve/(Re+H(i));-Ve*tan(phi(i,1))/(Re+H(i)) 0 -Vn/(Rn+H(i));Ve/(Re+H(i)) Vn/(Rn+H(i)) 0];

%   u = 0.26767823849628/3600; %рад/сек
  u = ((360 + 360/365.25)/24)*pi/(180*60*60);
  Udomik = [0 u*sin(phi(i,1)) -u*cos(phi(i,1));-u*sin(phi(i,1)) 0 0;u*cos(phi(i,1)) 0 0];
  
  ge = 9.7803253359;
  gp = 9.8321849378;
  
  gx = [0;0;(-ge*cos(phi(i,1))*cos(phi(i,1))-gp*sqrt(1-e2)*sin(phi(i,1))*sin(phi(i,1)))/sqrt(1-e2*sin(phi(i,1))*sin(phi(i,1)))];
  
  
  
  sum = (TETAdomik + 2*Udomik)*V_ + gx;
  
  fx = Vshtrix - sum;
  fz = S*fx;
  
%____________________________________4 часть___________________________________________
    
%     deltaFz0 = [randi([1,10]);
%                 randi([1,10]);
%                 randi([1,10])];
%     deltaFz0 = deltaFz0 * 1e-3;
    
%     buf = [randi([10,100])   0              0;
%              0               randi([10,100])  0;
%              0               0              randi([10,100])];
%     buf*1e-6;
    
%     a = 76.2;
%     b = 304.8;
%     buf2 = [0                   0                   0;
%             a+(b-a)*rand        0                   0;
%             a+(b-a)*rand        a+(b-a)*rand        0];
%     GAMMA = buf*1e-6 + buf2*1e-2;
%     GAMMA
    
    sigma = 10e-3;
    deltaFzs = [sigma*randn*0+1;
                sigma*randn*0;
                sigma*randn*0];
%     deltaFzs = deltaFzs * 1e-3;

%     fz = fz + deltaFz0 + GAMMA*fz + deltaFzs;
    fz = fz + deltaFzs;
%     deltaFzs
%     fz
    
%______________________________________________________________________________________
  
  fMatrix1(i-1,1) = T(i,1);
  fMatrix1(i-1,2) = fz(1,1);
  fMatrix1(i-1,3) = fz(2,1);
  fMatrix1(i-1,4) = fz(3,1);

%__________________________________________3-я часть_____________________________________________________
%     phi3 - угол фи' для 3 части
%     phi3, lam3, H3 - модельные величины

%     V1(i,1) = V1(i-1,1) + V2*(Ve*tan(phi(i,1))/(Re+H(i)) + 2*U*sin(phi(i,1))) - V3*(Ve/(Re+H(i)) + 2*U*cos(phi(i,1)));
%     U = (360 + 360/365.25)/24;
%     U = u;
    U = ((360 + 360/365.25)/24)*pi/(180*60*60);
    
    Re3 = A/sqrt(1-e2*(sin(phi3(i-1,1)))^2);
    Rn3 = A*(1-e2)/((sqrt(1-e2*(sin(phi3(i-1,1)))^2))^3);
    
    H3(i) = H3(i-1) + V3(i-1)*dT;
    phi3(i,1) = phi3(i-1,1) + V2(i-1)/(Rn3+H3(i-1));
    lam3(i,1) = lam3(i-1,1) + V1(i-1)/((Re3+H3(i-1))*cos(phi3(i-1,1)));

    omegaDomik3 = [0 V1(i-1)*tan(phi3(i-1,1))/(Re3+H3(i-1)) -V1(i-1)/(Re3+H3(i-1));
                -V1(i-1)*tan(phi3(i-1,1))/(Re3+H3(i-1)) 0 -V2(i-1)/(Rn3+H3(i-1));
                V1(i-1)/(Re3+H3(i-1)) V2(i-1)/(Rn3+H3(i-1)) 0];
%     omegaDomik3
%     TETAdomik
%     V1(i-1)
%     Ve
            
%     TETAdomik = [0 Ve*tan(phi(i,1))/(Re+H(i)) -Ve/(Re+H(i));-Ve*tan(phi(i,1))/(Re+H(i)) 0 -Vn/(Rn+H(i));Ve/(Re+H(i)) Vn/(Rn+H(i)) 0];
            
    Udomik3 = [0 U*sin(phi3(i-1,1)) -U*cos(phi3(i-1,1));
               -U*sin(phi3(i-1,1)) 0 0;
               U*cos(phi3(i-1,1)) 0 0];
    
    V3_ = [V1(i-1) V2(i-1) V3(i-1)]'; %вектор V'(модельная величина)
%     V3_ = V_;
    Gx3 = [0;0;(-ge*(cos(phi3(i-1,1)))^2-(gp*sqrt(1-e2)*(sin(phi3(i-1,1)))^2))/sqrt(1-e2*(sin(phi3(i-1,1)))^2)];

%     omegaDomik3
%     TETAdomik
%     Udomik3
%     Udomik
    
%     S = S';
    V3_der = (omegaDomik3 + 2*Udomik3)*V3_ + Gx3 + S'*fz; % вектор производных V1', V2', V3'(модельные)
    
    V1(i) = V1(i-1) + V3_der(1)*dT;
    V2(i) = V2(i-1) + V3_der(2)*dT;
    V3(i) = V3(i-1) + V3_der(3)*dT;
    
%________________________________________________________________________________________________________

end

% phi
% V3

H = H';
H3 = H3';

% plot(fMatrix1(:,2)); grid on;


Ve1 = Ve;
Vn1 = Vn;
Vu1 = Vu;
phi1 = phi(size(T,1),1);
lam1 = lam(size(T,1),1);
% flight = [T lam3*180/pi phi3*180/pi H3 roll pitch heading];
flight = [T lam*180/pi phi*180/pi H roll pitch heading];


%Y1 = V(end,1); Y2 = 120; DT = 5;
%T0 = T(end,1);
%T = [T0:dT:T0+DT]';
%x = (T-T0)/DT;
%V = (Y1-Y2)/2*cos(pi*x) + (Y1+Y2)/2;

%lam = 0*T + lam0;
%H = 0*T + H0;
%roll = 0*T;
%pitch = 0*T;
%heading = 0*T + psi0z
%i0 = size(phi,1);
%phi = phi(end,1);
% phi*180/pi
lam = lam(size(T,1),1);
phi = phi(size(T,1),1);

H = H0;
% H
roll = 0;
pitch = 0;
% heading = psi0;
% heading


%__________________________________________3-я часть_____________________________________________________
lam3 = lam3(size(T,1),1);
phi3 = phi3(size(T,1),1);

H3 = H0;
V1 = V1(size(T,1));
V2 = V2(size(T,1));
V3 = V3(size(T,1));
%________________________________________________________________________________________________________

TETA0 = 0;
TETAotr = 30/180*pi; % угол отрыва
TETA = 0;
Hbez = 10.7; % безопасная высота
Vbez = 1.3*Votr; % безопасная скорость
T2 = 2*Hbez/(Votr*sin(TETAotr));
T = [0:dT:T2]';
TETA2 = 0;
fMatrix2 = [];
for i = 2:size(T,1)
  roll(i,1) = 0;
  heading(i,1) = psi0;
   
  TETA = (TETA0-TETAotr)/2*cos(pi*T(i,1)/T2) + (TETA0+TETAotr)/2;
%   TETA2(i,1) = TETA;
  V = (Votr-Vbez)/2*cos(pi*T(i,1)/T2) + (Votr+Vbez)/2;
  Vn = V*cos(psi0)*cos(TETA);
  
  Rn = A*(1-e2)/((sqrt(1-e2*(sin(phi(i-1,1)))^2))^3);
%   Rn(i,1) = A*(1-e^2)/(sqrt(1-(e^2)*(sin(phi(i-1,1))^2))^3);
  Vu = V*sin(TETA);

  H(i) = H(i-1) + Vu*dT;

  phi(i,1) = phi(i-1,1) + (Vn/(Rn+H(i)))*dT;
  
  Re = A/sqrt(1-e2*(sin(phi(i,1)))^2);
  Ve = V*sin(psi0)*cos(TETA);
  lam(i,1) = lam(i-1,1) + Ve/((Re+H(i))*cos(phi(i,1)))*dT;
  
  pitch(i,1) = TETA;
  
  
  V_ = [Ve;Vn;Vu]; %  V_ - вектор V
  
  V_der = (pi/T2)*((Vbez-Votr)/2)*sin(pi*T(i,1)/T2);
  TETA_der = (pi/T2)*((TETAotr-TETA0)/2)*sin(pi*T(i,1)/T2);
  
  Ve_der = sin(psi0)*(V_der*cos(TETA) - V*sin(TETA)*TETA_der);
  Vn_der = cos(psi0)*(V_der*cos(TETA) - V*sin(TETA)*TETA_der);
  Vu_der = V_der*sin(TETA) + V*cos(TETA)*TETA_der;
  
  Vshtrix = [Ve_der; Vn_der; Vu_der];
%   Vshtrix = [-sin(psi0)*pi/T2*(Votr-Vbez)/2*sin(pi/T2*T(i,1));-cos(psi0)*pi/T2*(Votr-Vbez)/2*sin(pi/T2*T(i,1));Vu_der]; %  Vshtrix - вектор V'

  S = [cos(TETA)*sin(psi) cos(TETA)*cos(psi) sin(TETA);sin(gamma)*cos(psi)-sin(TETA)*cos(gamma)*sin(psi) -sin(TETA)*cos(gamma)*cos(psi)-sin(gamma)*sin(psi) cos(TETA)*cos(gamma);sin(gamma)*sin(TETA)*sin(psi)+cos(gamma)*cos(psi) sin(gamma)*sin(TETA)*cos(psi)-cos(gamma)*sin(psi) -sin(gamma)*cos(TETA)];
  
  TETAdomik = [0 Ve*tan(phi(i,1))/(Re+H(i)) -Ve/(Re+H(i));-Ve*tan(phi(i,1))/(Re+H(i)) 0 -Vn/(Rn+H(i));Ve/(Re+H(i)) Vn/(Rn+H(i)) 0];
%  TETAdomik
%   u = 0.26767823849628/3600; %рад/сек
  u = ((360 + 360/365.25)/24)*pi/(180*60*60);
  Udomik = [0 u*sin(phi(i,1)) -u*cos(phi(i,1));-u*sin(phi(i,1)) 0 0;u*cos(phi(i,1)) 0 0];
  
  ge = 9.7803253359;
  gp = 9.8321849378;
  
  gx = [0;0;(-ge*cos(phi(i,1))*cos(phi(i,1))-gp*sqrt(1-e2)*sin(phi(i,1))*sin(phi(i,1)))/sqrt(1-e2*sin(phi(i,1))*sin(phi(i,1)))];
  
  
  
  sum = (TETAdomik + 2*Udomik)*V_ + gx;
  
  fx = Vshtrix - sum;
  fz = S*fx;
  
  fMatrix2(i-1,1) = T(i,1);
  fMatrix2(i-1,2) = fz(1,1);
  fMatrix2(i-1,3) = fz(2,1);
  fMatrix2(i-1,4) = fz(3,1);

%____________________________________Погрешности___________________________________________
    
    sigma = 10e-3;
    deltaFzs = [sigma*randn*0+1;
                sigma*randn*0;
                sigma*randn*0];
            
    fz = fz + deltaFzs;
    
%______________________________________________________________________________________
   

end



%------------------------- Начало лабораторной работы 3-------------------------
T = [T1+dT:dT:T1+T2]';
% size(fMatrix2)
fMatrix2(:,1) = T;
fMatrix = [fMatrix1;fMatrix2];

lam_mod = lam0;       % долгота
phi_mod = phi0;      % широта
psi_mod = psi0;                          % направление(курс)

H_mod=H0;
T = [0:dT:T1]';

Vn_mod = 0;
Vu_mod = 0;
Ve_mod = 0;
TETA = 0;
gamma = 0;

V_vector_mod = [Ve_mod;
                Vn_mod;
                Vu_mod];
        
 Rn_mod = A*(1-e2)/((sqrt(1-e2*(sin(phi_mod))^2))^3);
 Re_mod = A/sqrt(1-e2*(sin(phi_mod))^2);
 
Spsi=[sin(psi_mod) cos(psi_mod) 0;
     -cos(psi_mod) sin(psi_mod) 0;
           0            0       1];
   
Steta=[  cos(TETA)  0 sin(TETA) ;
             0       1      0     ; 
         -sin(TETA) 0 cos(TETA)];
   
Sgamma= [1        0          0       ;
         0  -sin(gamma)  cos(gamma);
         0  -cos(gamma) -sin(gamma)];
        
S=(Sgamma*Steta*Spsi) ;
   
flight3=[]; 
   
U=((360+360/365.25)/24)*pi/(180*60*60);
   
for i = 2:size(T,1)
%    if i<=T1/dT
 
 kren=0;
tangaj=0;
kurs=psi_mod;

  Omega_krisha_mod = [                   0                             V_vector_mod(1)*tan(phi_mod)/(Re_mod+H_mod)                 -V_vector_mod(1)/(Re_mod+H_mod);
                    -V_vector_mod(1)*tan(phi_mod)/(Re_mod+H_mod)                           0                                    -V_vector_mod(2)/(Rn_mod+H_mod);
                       V_vector_mod(1)/(Re_mod+H_mod)                        V_vector_mod(2)/(Rn_mod+H_mod)                                       0         ];
      
   U_krisha_mod = [         0           U*sin(phi_mod)        -U*cos(phi_mod);
                    -U*sin(phi_mod)           0                       0      ;
                     U*cos(phi_mod)           0                       0      ];     
 
 Norm_g=(-ge*cos(phi_mod)*cos(phi_mod)-gp*sqrt(1-e2)*sin(phi_mod)*sin(phi_mod))/sqrt(1-e2*sin(phi_mod)*sin(phi_mod));
 Gx_mod = [   0;  0;   Norm_g]; 
   
 
 job1 = (Omega_krisha_mod+2*U_krisha_mod)*V_vector_mod;
 
%  fMatrix(i-1,1)
 fz = [fMatrix(i-1,2) fMatrix(i-1,3) fMatrix(i-1,4)]';
 job2 = Gx_mod+S'*fz;  
  
 V_vector_mod=(job1+job2)*dT+V_vector_mod;
V_vector_mod;

phi_mod= phi_mod + (V_vector_mod(2)/(Rn_mod+H_mod))*dT;
lam_mod = lam_mod + (V_vector_mod(1)/((Re_mod+H_mod)*cos(phi_mod)))*dT;
H_mod=H_mod+V_vector_mod(3)*dT;

Rn_mod = A*(1-e2)/((sqrt(1-e2*(sin(phi_mod ))^2))^3);
Re_mod = A/sqrt(1-e2*(sin(phi_mod ))^2);

% V_vector_mod(3)

flight3 = [flight3;T(i) lam_mod*180/pi phi_mod*180/pi H_mod kren tangaj kurs];
%   
% flight3 = [flight3;T(i) lam_mod*180/pi phi_mod*180/pi H_mod kren tangaj kurs];
%    end
%     
end



%___________________________________Взлёт______________________________________

lam_mod = lam_mod;       % долгота
phi_mod = phi_mod;      % широта
psi_mod = psi0;                          % направление(курс)
H_mod=H_mod;
T = [0:dT:T2]';

% Ve_mod = Ve1;
% Vn_mod =Vn1;
% Vu_mod = Vu1;
Ve_mod = V_vector_mod(1);
Vn_mod = V_vector_mod(2);
Vu_mod = V_vector_mod(3);
% V_vector_mod(3)
% Vu_mod
TETA0 = 0;
gamma = 0;

V_vector_mod = [Ve_mod;
                Vn_mod;
                Vu_mod];
        
 Rn_mod = A*(1-e2)/((sqrt(1-e2*(sin(phi_mod))^2))^3);
 Re_mod = A/sqrt(1-e2*(sin(phi_mod))^2);
 
% flight3 = [];

    for i = 2:size(T,1)
        
        TETA = (TETA0-TETAotr)/2*cos(pi*T(i,1)/T2) + (TETA0+TETAotr)/2;
    
        Spsi=[sin(psi_mod) cos(psi_mod) 0;
              -cos(psi_mod) sin(psi_mod) 0;
                0            0          1];
   
        Steta=[  cos(TETA)  0 sin(TETA) ;
                     0       1      0     ; 
                 -sin(TETA) 0 cos(TETA)];

        Sgamma= [1        0          0       ;
                 0  -sin(gamma)  cos(gamma);
                 0  -cos(gamma) -sin(gamma)];

        S=(Sgamma*Steta*Spsi);
        
        kren=0;
%         tangaj=0;
        kurs=psi_mod;

          Omega_krisha_mod = [                   0                             V_vector_mod(1)*tan(phi_mod)/(Re_mod+H_mod)                 -V_vector_mod(1)/(Re_mod+H_mod);
                            -V_vector_mod(1)*tan(phi_mod)/(Re_mod+H_mod)                           0                                    -V_vector_mod(2)/(Rn_mod+H_mod);
                               V_vector_mod(1)/(Re_mod+H_mod)                        V_vector_mod(2)/(Rn_mod+H_mod)                                       0         ];

%          Omega_krisha_mod
           U_krisha_mod = [         0           U*sin(phi_mod)        -U*cos(phi_mod);
                            -U*sin(phi_mod)           0                       0      ;
                             U*cos(phi_mod)           0                       0      ];     

         Norm_g=(-ge*cos(phi_mod)*cos(phi_mod)-gp*sqrt(1-e2)*sin(phi_mod)*sin(phi_mod))/sqrt(1-e2*sin(phi_mod)*sin(phi_mod));
         Gx_mod = [   0;  0;   Norm_g];


         job1 = (Omega_krisha_mod+2*U_krisha_mod)*V_vector_mod;

        %  fMatrix(i-1,1)
         fz = [fMatrix2(i-1,2) fMatrix2(i-1,3) fMatrix2(i-1,4)]';
         job2 = Gx_mod+S'*fz;  

         V_vector_mod = V_vector_mod + (job1+job2)*dT;
%         V_vector_mod

        phi_mod= phi_mod + (V_vector_mod(2)/(Rn_mod+H_mod))*dT;
        lam_mod = lam_mod + (V_vector_mod(1)/((Re_mod+H_mod)*cos(phi_mod)))*dT;
        H_mod=H_mod+V_vector_mod(3)*dT;
%         H_mod = H(i);

        Rn_mod = A*(1-e2)/((sqrt(1-e2*(sin(phi_mod ))^2))^3);
        Re_mod = A/sqrt(1-e2*(sin(phi_mod ))^2);

%         H_mod
        tangaj=TETA;

        flight3 = [flight3;(T(i)+T1) lam_mod*180/pi phi_mod*180/pi H_mod kren tangaj kurs];
        %   
        % flight3 = [flight3;T(i) lam_mod*180/pi phi_mod*180/pi H_mod kren tangaj kurs];
        %    end
        %     
    end

%______________________________________________________________________________

% lam1 = lam(size(T,1),1);
% phi1 = phi(size(T,1),1);


% flight3

H = H';
H3 = H3';
% phi*180/pi
T = [T1+dT:dT:T1+T2]';
% fMatrix1(:,1)
fMatrix2(:,1) = T;

fMatrix = [fMatrix1;fMatrix2];

% fMatrix
save fmatrix.txt fMatrix -ascii

T = [T1:dT:T1+T2]';


flight = [flight; T lam*180/pi phi*180/pi H roll pitch heading];



H = H(size(T,1),1);
roll = 0;
pitch = TETAotr;
heading = psi0;

lam = flight(size(flight,1),2)*pi/180;
phi = flight(size(flight,1),3)*pi/180;

TETAotr = 30/180*pi; % угол отрыва
TETA0 = 0;
TETA = TETAotr;
Hkr = 3500; % крейсерская высота
Vkr = 33.611; % крейсерская скорость
T3 = 4*Hkr/(Vkr*sin(TETAotr));
T = [0:dT:T3]';
fMatrix3 = [];
%_______________________________________5 часть________________________________

for i = 2:size(T,1)
%  TETAdomik = 0;
  roll(i,1) = 0;
  heading(i,1) = psi0;
   
  TETA = (TETAotr-TETA0)/2*cos(pi*T(i,1)/T3) + (TETAotr+TETA0)/2;
  V = (Vbez-Vkr)/2*cos(pi*T(i,1)/T3) + (Vbez+Vkr)/2;
  Vn = V*cos(psi0)*cos(TETA);
  
  Rn = A*(1-e2)/((sqrt(1-e2*(sin(phi(i-1,1)))^2))^3);
%   Rn(i,1) = A*(1-e^2)/(sqrt(1-(e^2)*(sin(phi(i-1,1))^2))^3);
  Vu = V*sin(TETA);

  H(i) = H(i-1) + Vu*dT;

  phi(i,1) = phi(i-1,1) + (Vn/(Rn+H(i)))*dT;
  
  Re = A/sqrt(1-e2*(sin(phi(i,1)))^2);
  Ve = V*sin(psi0)*cos(TETA);
  lam(i,1) = lam(i-1,1) + Ve/((Re+H(i))*cos(phi(i,1)))*dT;
  
  pitch(i,1) = TETA;
  
  
  V_ = [Ve;Vn;Vu]; %  V_ - вектор V
  
  V_der = (pi/T3)*((Vkr-Vbez)/2)*sin(pi*T(i,1)/T3);
  TETA_der = (pi/T3)*((TETA0-TETAotr)/2)*sin(pi*T(i,1)/T3);
  
  Ve_der = sin(psi0)*(V_der*cos(TETA) - V*sin(TETA)*TETA_der);
  Vn_der = cos(psi0)*(V_der*cos(TETA) - V*sin(TETA)*TETA_der);
  Vu_der = V_der*sin(TETA) + V*cos(TETA)*TETA_der;
  
  Vshtrix = [Ve_der; Vn_der; Vu_der];
%   Vshtrix = [-sin(psi0)*pi/T2*(Votr-Vbez)/2*sin(pi/T2*T(i,1));-cos(psi0)*pi/T2*(Votr-Vbez)/2*sin(pi/T2*T(i,1));Vu_der]; %  Vshtrix - вектор V'

  S = [cos(TETA)*sin(psi) cos(TETA)*cos(psi) sin(TETA);sin(gamma)*cos(psi)-sin(TETA)*cos(gamma)*sin(psi) -sin(TETA)*cos(gamma)*cos(psi)-sin(gamma)*sin(psi) cos(TETA)*cos(gamma);sin(gamma)*sin(TETA)*sin(psi)+cos(gamma)*cos(psi) sin(gamma)*sin(TETA)*cos(psi)-cos(gamma)*sin(psi) -sin(gamma)*cos(TETA)];
  
  TETAdomik = [0 Ve*tan(phi(i,1))/(Re+H(i)) -Ve/(Re+H(i));-Ve*tan(phi(i,1))/(Re+H(i)) 0 -Vn/(Rn+H(i));Ve/(Re+H(i)) Vn/(Rn+H(i)) 0];
%  TETAdomik
%   u = 0.26767823849628/3600; %рад/сек
  u = ((360 + 360/365.25)/24)*pi/(180*60*60);
  Udomik = [0 u*sin(phi(i,1)) -u*cos(phi(i,1));-u*sin(phi(i,1)) 0 0;u*cos(phi(i,1)) 0 0];
  
  ge = 9.7803253359;
  gp = 9.8321849378;
  
  gx = [0;0;(-ge*cos(phi(i,1))*cos(phi(i,1))-gp*sqrt(1-e2)*sin(phi(i,1))*sin(phi(i,1)))/sqrt(1-e2*sin(phi(i,1))*sin(phi(i,1)))];
  
  
  
  sum = (TETAdomik + 2*Udomik)*V_ + gx;
  
  fx = Vshtrix - sum;
  fz = S*fx;
  
  fMatrix3(i-1,1) = T(i,1);
  fMatrix3(i-1,2) = fz(1,1);
  fMatrix3(i-1,3) = fz(2,1);
  fMatrix3(i-1,4) = fz(3,1);
  
%_____________________________________Погрешности___________________________________________
    
    sigma = 10e-3;
    deltaFzs = [sigma*randn*0+1;
                sigma*randn*0;
                sigma*randn*0];

    fz = fz + deltaFzs*0;
%______________________________________________________________________________________

end
%___________________________________________________________________________________________________________


% flight3


%___________________________________Модельная траектория для 5 части______________________________________

lam_mod = lam_mod;       % долгота
% phi_mod*180/pi
phi_mod = phi_mod;      % широта
psi_mod = psi0;                          % направление(курс)
H_mod=H_mod;
T = [0:dT:T3]';

% Ve_mod = Ve1;
% Vn_mod =Vn1;
% Vu_mod = Vu1;
Ve_mod = V_vector_mod(1);
Vn_mod = V_vector_mod(2);
Vu_mod = V_vector_mod(3);
% V_vector_mod(3)
% Vu_mod
TETA0 = 0;
gamma = 0;

V_vector_mod = [Ve_mod;
                Vn_mod;
                Vu_mod];
        
 Rn_mod = A*(1-e2)/((sqrt(1-e2*(sin(phi_mod))^2))^3);
 Re_mod = A/sqrt(1-e2*(sin(phi_mod))^2);
 
% flight3 = [];

    for i = 2:size(T,1)
        
        TETA = (TETAotr-TETA0)/2*cos(pi*T(i,1)/T3) + (TETAotr+TETA0)/2;
    
        Spsi=[sin(psi_mod) cos(psi_mod) 0;
              -cos(psi_mod) sin(psi_mod) 0;
                0            0          1];
   
        Steta=[  cos(TETA)  0 sin(TETA) ;
                     0       1      0     ; 
                 -sin(TETA) 0 cos(TETA)];

        Sgamma= [1        0          0       ;
                 0  -sin(gamma)  cos(gamma);
                 0  -cos(gamma) -sin(gamma)];

        S=(Sgamma*Steta*Spsi);
        
        kren=0;
%         tangaj=0;
        kurs=psi_mod;

          Omega_krisha_mod = [                   0                             V_vector_mod(1)*tan(phi_mod)/(Re_mod+H_mod)                 -V_vector_mod(1)/(Re_mod+H_mod);
                            -V_vector_mod(1)*tan(phi_mod)/(Re_mod+H_mod)                           0                                    -V_vector_mod(2)/(Rn_mod+H_mod);
                               V_vector_mod(1)/(Re_mod+H_mod)                        V_vector_mod(2)/(Rn_mod+H_mod)                                       0         ];

%          Omega_krisha_mod
           U_krisha_mod = [         0           U*sin(phi_mod)        -U*cos(phi_mod);
                            -U*sin(phi_mod)           0                       0      ;
                             U*cos(phi_mod)           0                       0      ];     

         Norm_g=(-ge*cos(phi_mod)*cos(phi_mod)-gp*sqrt(1-e2)*sin(phi_mod)*sin(phi_mod))/sqrt(1-e2*sin(phi_mod)*sin(phi_mod));
         Gx_mod = [   0;  0;   Norm_g];


         job1 = (Omega_krisha_mod+2*U_krisha_mod)*V_vector_mod;

        %  fMatrix(i-1,1)
         fz = [fMatrix3(i-1,2) fMatrix3(i-1,3) fMatrix3(i-1,4)]';
         job2 = Gx_mod+S'*fz;  

         V_vector_mod = V_vector_mod + (job1+job2)*dT;
%         V_vector_mod

        phi_mod= phi_mod + (V_vector_mod(2)/(Rn_mod+H_mod))*dT;
        lam_mod = lam_mod + (V_vector_mod(1)/((Re_mod+H_mod)*cos(phi_mod)))*dT;
        H_mod=H_mod+V_vector_mod(3)*dT;
%         H_mod = H(i);

        Rn_mod = A*(1-e2)/((sqrt(1-e2*(sin(phi_mod ))^2))^3);
        Re_mod = A/sqrt(1-e2*(sin(phi_mod ))^2);

        tangaj=TETA;

        flight3 = [flight3;(T(i)+T1+T2) lam_mod*180/pi phi_mod*180/pi H_mod kren tangaj kurs];
        %   
        % flight3 = [flight3;T(i) lam_mod*180/pi phi_mod*180/pi H_mod kren tangaj kurs];
        %    end
        %     
    end

%________________________________________________________________________________________________






% hold on;
% plot(flight(:,1), flight(:,7)); grid on;
% plot(flight3(:,1), flight3(:,7)); grid on;

% plot(fMatrix); grid on;

H = H';
T = [T1+T2:dT:T1+T2+T3]';
% size()
flight = [flight; T lam*180/pi phi*180/pi H roll pitch heading];


save flight.txt flight -ascii
tgeo2kml(flight3)
tgeo2kml_(flight)