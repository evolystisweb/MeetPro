import { Button } from "@/components/ui/button";
import { Calendar, Clock, Users, Zap } from "lucide-react";
import { useNavigate } from "react-router-dom";
import heroImage from "@/assets/hero-calendar.jpg";

const Hero = () => {
  const navigate = useNavigate();

  return (
    <section className="relative overflow-hidden bg-gradient-to-br from-background via-background to-secondary/30">
      <div className="container mx-auto px-4 py-20 lg:py-32">
        <div className="grid lg:grid-cols-2 gap-12 items-center">
          <div className="space-y-8 animate-fade-in">
            <div className="space-y-4">
              <h1 className="text-4xl md:text-6xl font-bold leading-tight">
                Planifiez vos{" "}
                <span className="hero-gradient bg-clip-text text-transparent">
                  rendez-vous
                </span>{" "}
                en toute simplicité
              </h1>
              <p className="text-xl text-muted-foreground max-w-2xl">
                Interface moderne et intuitive pour organiser vos réunions physiques et en ligne. 
                Connectez tous vos calendriers et automatisez vos rappels.
              </p>
            </div>

            <div className="flex flex-col sm:flex-row gap-4">
              <Button 
                variant="hero" 
                size="lg" 
                className="text-lg px-8 py-6 pulse-glow"
                onClick={() => navigate('/auth')}
              >
                Commencer gratuitement
              </Button>
              <Button 
                variant="outline" 
                size="lg" 
                className="text-lg px-8 py-6"
                onClick={() => navigate('/auth')}
              >
                Voir la démo
              </Button>
            </div>

            <div className="grid grid-cols-2 md:grid-cols-4 gap-6 pt-8 floating">
              <div className="flex flex-col items-center space-y-2 group">
                <div className="p-3 rounded-full bg-primary/10 group-hover:bg-primary/20 transition-all duration-300 group-hover:scale-110 group-hover:rotate-12">
                  <Calendar className="h-6 w-6 text-primary" />
                </div>
                <span className="text-sm font-medium">Calendriers multiples</span>
              </div>
              
              <div className="flex flex-col items-center space-y-2 group">
                <div className="p-3 rounded-full bg-accent/10 group-hover:bg-accent/20 transition-all duration-300 group-hover:scale-110 group-hover:rotate-12">
                  <Clock className="h-6 w-6 text-accent" />
                </div>
                <span className="text-sm font-medium">Disponibilité flexible</span>
              </div>
              
              <div className="flex flex-col items-center space-y-2 group">
                <div className="p-3 rounded-full bg-primary/10 group-hover:bg-primary/20 transition-all duration-300 group-hover:scale-110 group-hover:rotate-12">
                  <Users className="h-6 w-6 text-primary" />
                </div>
                <span className="text-sm font-medium">Réunions collaboratives</span>
              </div>
              
              <div className="flex flex-col items-center space-y-2 group">
                <div className="p-3 rounded-full bg-accent/10 group-hover:bg-accent/20 transition-all duration-300 group-hover:scale-110 group-hover:rotate-12">
                  <Zap className="h-6 w-6 text-accent" />
                </div>
                <span className="text-sm font-medium">Intégrations avancées</span>
              </div>
            </div>
          </div>

          <div className="relative animate-slide-up">
            {/* Éléments 3D flottants en arrière-plan */}
            <div className="absolute inset-0 overflow-visible">
              {/* Cube 3D rotatif */}
              <div className="absolute top-10 right-20 w-32 h-32 animate-float"
                   style={{
                     transformStyle: 'preserve-3d',
                     animation: 'float 6s ease-in-out infinite, rotate3d 10s linear infinite'
                   }}>
                <div className="absolute inset-0 bg-gradient-to-br from-blue-500/30 to-purple-500/30 backdrop-blur-sm rounded-2xl"
                     style={{
                       transform: 'rotateX(30deg) rotateY(30deg)',
                       boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)'
                     }}></div>
              </div>

              {/* Cercle 3D pulsant */}
              <div className="absolute bottom-20 left-10 w-40 h-40"
                   style={{
                     transformStyle: 'preserve-3d',
                     animation: 'pulse 4s ease-in-out infinite, float 8s ease-in-out infinite'
                   }}>
                <div className="absolute inset-0 rounded-full bg-gradient-to-br from-green-400/20 to-blue-500/20 backdrop-blur-sm"
                     style={{
                       transform: 'rotateX(60deg)',
                       boxShadow: '0 35px 60px -15px rgba(34, 197, 94, 0.3)'
                     }}></div>
              </div>

              {/* Éléments de calendrier flottants */}
              <div className="absolute top-1/4 left-0 w-20 h-20 bg-white/10 backdrop-blur-md rounded-lg shadow-xl"
                   style={{
                     animation: 'float 5s ease-in-out infinite',
                     transform: 'perspective(1000px) rotateY(20deg) rotateX(10deg)',
                     animationDelay: '0.5s'
                   }}>
                <div className="p-2 text-center">
                  <div className="text-xs text-blue-400 font-bold">MAR</div>
                  <div className="text-2xl font-bold text-white">15</div>
                </div>
              </div>

              {/* Icône horloge 3D */}
              <div className="absolute top-1/2 right-0 w-24 h-24 bg-gradient-to-br from-orange-400/20 to-red-500/20 backdrop-blur-md rounded-full shadow-2xl flex items-center justify-center"
                   style={{
                     animation: 'float 7s ease-in-out infinite, spin-slow 20s linear infinite',
                     transform: 'perspective(1000px) rotateY(-20deg)',
                     animationDelay: '1s'
                   }}>
                <Clock className="h-12 w-12 text-orange-400" />
              </div>
            </div>

            <div className="relative rounded-2xl overflow-hidden shadow-2xl transform hover:scale-[1.02] transition-all duration-500"
                 style={{
                   transformStyle: 'preserve-3d',
                   transform: 'perspective(1500px) rotateY(-5deg)'
                 }}>
              <img
                src={heroImage}
                alt="Interface de planification MeetSync"
                className="w-full h-auto"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-primary/20 via-transparent to-transparent"></div>

              {/* Effet de lumière 3D */}
              <div className="absolute inset-0 bg-gradient-to-tr from-transparent via-white/5 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-500"
                   style={{
                     transform: 'translateZ(20px)'
                   }}></div>
            </div>

            {/* Particules flottantes */}
            <div className="absolute -top-6 -right-6 w-24 h-24 rounded-full hero-gradient opacity-20 blur-xl"
                 style={{ animation: 'pulse 3s ease-in-out infinite, float 5s ease-in-out infinite' }}></div>
            <div className="absolute -bottom-8 -left-8 w-32 h-32 rounded-full bg-accent/20 blur-xl"
                 style={{ animation: 'pulse 4s ease-in-out infinite, float 6s ease-in-out infinite 1s' }}></div>
            <div className="absolute top-1/3 -left-12 w-20 h-20 rounded-full bg-blue-500/10 blur-lg"
                 style={{ animation: 'float 7s ease-in-out infinite 2s' }}></div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Hero;